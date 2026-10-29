#!/bin/bash
# Pemakaian: ./push.sh "pesan commit"
REPO="/c/xampp/htdocs"
BRANCH="main"
MAX_MB=50
MSG="${1:-update $(date '+%d-%m-%Y %H:%M')}"

die()  { echo ""; echo "GAGAL: $*"; exit 1; }
info() { echo ">> $*"; }

# 1. Pastikan folder & repo benar
cd "$REPO" 2>/dev/null || die "folder $REPO tidak ditemukan"
git rev-parse --is-inside-work-tree >/dev/null 2>&1 || die "$REPO bukan repo git"
git remote get-url origin >/dev/null 2>&1 || die "remote 'origin' belum diatur"
[ -n "$(git config user.email)" ] || die "identitas git belum diatur. Jalankan: git config --global user.email \"email@kamu.com\" dan git config --global user.name \"Nama\""

git config core.safecrlf false
GITDIR=$(git rev-parse --git-dir)
grep -qx "push.sh" "$GITDIR/info/exclude" 2>/dev/null || echo "push.sh" >> "$GITDIR/info/exclude"

# 2. Bersihkan keadaan tersangkut
[ -f "$GITDIR/index.lock" ] && { info "Menghapus index.lock lama"; rm -f "$GITDIR/index.lock"; }
if [ -d "$GITDIR/rebase-merge" ] || [ -d "$GITDIR/rebase-apply" ]; then
  info "Ada rebase tersangkut, dibatalkan (commit kamu tetap aman)"
  git rebase --abort || die "tidak bisa membatalkan rebase"
fi
[ -f "$GITDIR/MERGE_HEAD" ] && { info "Ada merge tersangkut, dibatalkan"; git merge --abort; }

# version.json dikelola otomatis oleh GitHub Actions, jangan ikut diubah dari sini
git checkout -- version.json 2>/dev/null

# 3. Siapkan file
git add -A
git reset -q -- application/config/database.php application/config/config.php 2>/dev/null
git diff --cached --name-only -z | while IFS= read -r -d '' f; do
  [ -f "$f" ] || continue
  sz=$(stat -c%s "$f" 2>/dev/null || echo 0)
  if [ "$sz" -gt $((MAX_MB*1024*1024)) ]; then
    echo "   DILEWATI (lebih dari ${MAX_MB}MB): $f"
    git reset -q -- "$f"
  fi
done

info "File yang akan dikirim:"
git diff --cached --name-status
echo ""

if ! git diff --cached --quiet; then
  git commit -q -m "$MSG" || die "commit gagal"
  info "Commit dibuat: $MSG"
fi

# 4. Sinkron dengan GitHub (tangani konflik version.json otomatis)
sync_remote() {
  git fetch origin "$BRANCH" || return 1
  git pull --rebase --autostash origin "$BRANCH" && return 0
  for i in 1 2 3 4 5; do
    others=$(git diff --name-only --diff-filter=U | grep -v '^version.json$')
    if [ -n "$others" ]; then
      git rebase --abort 2>/dev/null
      echo "Konflik di file berikut perlu diselesaikan manual:"
      echo "$others"
      return 1
    fi
    git checkout --ours version.json 2>/dev/null
    git add version.json 2>/dev/null
    GIT_EDITOR=true git rebase --continue && return 0
  done
  return 1
}

info "Sinkron dengan GitHub..."
sync_remote || die "sinkron gagal (lihat pesan di atas). Commit lokal kamu tetap aman."

# 5. Kirim, ulangi bila ditolak
AHEAD=$(git rev-list --count "origin/$BRANCH..HEAD" 2>/dev/null || echo 0)
if [ "$AHEAD" = "0" ]; then
  info "Tidak ada yang perlu dikirim. GitHub sudah sama dengan komputer ini."
  exit 0
fi

for i in 1 2 3; do
  info "Mengirim $AHEAD commit (percobaan $i/3)..."
  if git push origin "$BRANCH"; then
    echo ""
    info "BERHASIL. Pantau rilis: https://github.com/suekarn/Examly/actions"
    exit 0
  fi
  info "Push ditolak, sinkron ulang..."
  sync_remote || die "sinkron ulang gagal"
done
die "push gagal 3 kali. Periksa koneksi internet atau login GitHub."
