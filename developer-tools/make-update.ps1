<#
.SYNOPSIS
  Membuat paket update Examly dan merilisnya ke GitHub (untuk libraries/App_updater.php).

.DESCRIPTION
  1. Menyusun isi update dari C:\xampp\htdocs (tanpa data/konfigurasi sekolah)
  2. Membuat update-X.Y.Z.zip (path memakai "/", ada _update/migrate.sql dan _update/delete.txt jika ada)
  3. Membuat release di GitHub dan mengunggah zip
  4. Menulis version.json (version, released_at, changelog, url, sha256) lalu commit + push HANYA file itu
  5. Memperbarui application\config\version.php di PC Anda ke versi baru

  Simpan di: C:\xampp\htdocs\developer-tools\make-update.ps1

.EXAMPLE
  .\make-update.ps1 -Version 1.0.1 -DryRun
  .\make-update.ps1 -Version 1.0.1 -Changelog "Perbaikan login siswa"
  .\make-update.ps1 -Version 1.1.0 -Changelog "Fitur baru" -MinVersion 1.0.0 -IncludeApk
#>
param(
    [Parameter(Mandatory = $true)][string]$Version,
    [string]$Changelog   = '',
    [string]$MinVersion  = '',
    [string]$RequiresPhp = '',
    [string]$Repo        = 'suekarn/Examly',
    [string]$Branch      = 'main',
    [switch]$IncludeApk,
    [switch]$DryRun,
    [switch]$Yes
)

$ErrorActionPreference = 'Stop'

# ---------------------------------------------------------------- pengaturan
# Folder (relatif terhadap htdocs) yang TIDAK ikut paket update
$ExcludeDirs = @(
    '.git', '.idea', 'backups', 'developer-tools', 'license-server', 'installer',
    'uploads', 'docs',
    'application\cache', 'application\logs', 'application\updater_data'
)
# File (relatif terhadap htdocs) yang TIDAK ikut paket update
$ExcludeFiles = @(
    'application\config\database.php',
    'application\config\config.php',
    'application\config\updater.php',
    'application\config\version.php',
    'version.json', 'examly.apk', 'license.txt', 'garudacbt.iml', '.gitignore'
)

# ---------------------------------------------------------------- bantuan
function Write-Utf8NoBom([string]$Path, [string]$Text) {
    [System.IO.File]::WriteAllText($Path, $Text, (New-Object System.Text.UTF8Encoding($false)))
}
function Step([string]$Text) { Write-Host ''; Write-Host "== $Text" -ForegroundColor Cyan }
function Run-Quiet([scriptblock]$Block) {
    $old = $ErrorActionPreference
    $ErrorActionPreference = 'Continue'
    try { & $Block *> $null } finally { $ErrorActionPreference = $old }
    return $LASTEXITCODE
}

$ToolsDir = $PSScriptRoot
$Root     = (Resolve-Path (Join-Path $ToolsDir '..')).Path.TrimEnd('\')
$Dist     = Join-Path $ToolsDir 'dist'
$ZipName  = "update-$Version.zip"
$ZipPath  = Join-Path $Dist $ZipName
$Stage    = Join-Path $env:TEMP "examly-stage-$Version"

# ---------------------------------------------------------------- validasi
Step 'Memeriksa'
if ($Version -notmatch '^\d+(\.\d+){1,3}$') { throw "Format versi harus seperti 1.0.1 (bukan '$Version')." }
if ($MinVersion -ne '' -and $MinVersion -notmatch '^\d+(\.\d+){1,3}$') { throw "Format -MinVersion salah: $MinVersion" }

$VersionFile = Join-Path $Root 'application\config\version.php'
$Current = '0.0.0'
if (Test-Path $VersionFile) {
    $m = [regex]::Match((Get-Content $VersionFile -Raw), "app_version'\]\s*=\s*'([^']+)'")
    if ($m.Success) { $Current = $m.Groups[1].Value }
}
if ([version]$Version -le [version]$Current) {
    throw "Versi baru ($Version) harus lebih tinggi dari versi sekarang ($Current)."
}
Write-Host "Versi sekarang : $Current"
Write-Host "Versi rilis    : $Version"
Write-Host "Folder aplikasi: $Root"

if (-not $DryRun) {
    foreach ($t in 'git', 'gh') {
        if (-not (Get-Command $t -ErrorAction SilentlyContinue)) { throw "$t belum terpasang." }
    }
    if ((Run-Quiet { gh auth status }) -ne 0) { throw 'Belum login GitHub. Jalankan: gh auth login' }

    Push-Location $Root
    try {
        if ((Run-Quiet { git rev-parse --is-inside-work-tree }) -ne 0) {
            throw "Folder $Root belum menjadi repo Git."
        }
        $br = (git branch --show-current).Trim()
        if ($br -ne $Branch) { throw "Branch aktif '$br', seharusnya '$Branch'." }
        $remote = (git remote get-url origin).Trim()
        if ($remote -notmatch [regex]::Escape($Repo)) { throw "Remote origin ($remote) bukan $Repo." }
    } finally { Pop-Location }

    if ((Run-Quiet { gh release view "v$Version" --repo $Repo }) -eq 0) {
        throw "Rilis v$Version sudah ada di GitHub. Gunakan nomor versi yang baru."
    }
}

# ---------------------------------------------------------------- susun paket
Step 'Menyusun isi update'
if (Test-Path $Stage) { Remove-Item $Stage -Recurse -Force }
New-Item -ItemType Directory -Path $Stage | Out-Null

$xd = $ExcludeDirs | ForEach-Object { Join-Path $Root $_ }
robocopy $Root $Stage /E /XD $xd /NFL /NDL /NJH /NJS /NP | Out-Null
if ($LASTEXITCODE -ge 8) { throw "robocopy gagal (kode $LASTEXITCODE)." }

foreach ($f in $ExcludeFiles) {
    $p = Join-Path $Stage $f
    if (Test-Path $p) { Remove-Item $p -Force }
}

# Migrasi database & daftar file yang dihapus (khusus rilis ini)
$SqlSrc = Join-Path $ToolsDir 'migrate.sql'
$DelSrc = Join-Path $ToolsDir 'delete.txt'
$hasSql = Test-Path $SqlSrc
$hasDel = Test-Path $DelSrc
if ($hasSql -or $hasDel) { New-Item -ItemType Directory -Path (Join-Path $Stage '_update') | Out-Null }
if ($hasSql) { Copy-Item $SqlSrc (Join-Path $Stage '_update\migrate.sql'); Write-Host 'Menyertakan migrate.sql' }
if ($hasDel) { Copy-Item $DelSrc (Join-Path $Stage '_update\delete.txt');  Write-Host 'Menyertakan delete.txt' }

# Pengaman: jangan sampai data sekolah ikut terkirim
foreach ($bad in 'application\config\database.php', 'application\config\config.php', 'uploads', 'license.txt') {
    if (Test-Path (Join-Path $Stage $bad)) { throw "Terlarang ada di paket: $bad" }
}

$files = Get-ChildItem $Stage -Recurse -File -Force
if (-not $files) { throw 'Tidak ada file untuk dipaketkan.' }

# ---------------------------------------------------------------- zip
Step 'Membuat zip'
if (-not (Test-Path $Dist)) { New-Item -ItemType Directory -Path $Dist | Out-Null }
if (Test-Path $ZipPath) { Remove-Item $ZipPath -Force }

Add-Type -AssemblyName System.IO.Compression
Add-Type -AssemblyName System.IO.Compression.FileSystem
$base = $Stage.TrimEnd('\')
$fs  = [System.IO.File]::Open($ZipPath, [System.IO.FileMode]::Create)
$zip = New-Object System.IO.Compression.ZipArchive($fs, [System.IO.Compression.ZipArchiveMode]::Create)
try {
    foreach ($f in $files) {
        $rel = $f.FullName.Substring($base.Length + 1).Replace('\', '/')
        [System.IO.Compression.ZipFileExtensions]::CreateEntryFromFile(
            $zip, $f.FullName, $rel, [System.IO.Compression.CompressionLevel]::Optimal) | Out-Null
    }
} finally {
    $zip.Dispose()
    $fs.Dispose()
}
Remove-Item $Stage -Recurse -Force

$Hash   = (Get-FileHash $ZipPath -Algorithm SHA256).Hash.ToLower()
$SizeMB = [math]::Round((Get-Item $ZipPath).Length / 1MB, 2)
Write-Host "File   : $($files.Count)"
Write-Host "Ukuran : $SizeMB MB"
Write-Host "SHA256 : $Hash"
Write-Host "Zip    : $ZipPath"

# ---------------------------------------------------------------- manifest
$Notes = $Changelog
if ($Notes -eq '') { $Notes = "Pembaruan versi $Version" }
$Apk = Join-Path $Root 'examly.apk'

$manifest = [ordered]@{
    version     = $Version
    released_at = (Get-Date -Format 'yyyy-MM-dd')
    changelog   = $Notes
    url         = "https://github.com/$Repo/releases/download/v$Version/$ZipName"
    sha256      = $Hash
}
if ($MinVersion -ne '')  { $manifest['min_version']  = $MinVersion }
if ($RequiresPhp -ne '') { $manifest['requires_php'] = $RequiresPhp }
if ($IncludeApk -and (Test-Path $Apk)) {
    $manifest['apk_url'] = "https://github.com/$Repo/releases/download/v$Version/examly.apk"
}
$json = $manifest | ConvertTo-Json

if ($DryRun) {
    $preview = Join-Path $Dist 'version.preview.json'
    Write-Utf8NoBom $preview $json
    Write-Host ''
    Write-Host 'DRY RUN selesai. Tidak ada yang dikirim ke GitHub dan tidak ada file aplikasi yang diubah.' -ForegroundColor Yellow
    Write-Host "Pratinjau version.json: $preview"
    return
}

# ---------------------------------------------------------------- konfirmasi
if (-not $Yes) {
    Write-Host ''
    $ans = Read-Host "Rilis v$Version ke GitHub ($Repo)? Ketik y untuk lanjut"
    if ($ans -ne 'y') { Write-Host 'Dibatalkan.'; return }
}

# ---------------------------------------------------------------- GitHub release
Step 'Mengunggah ke GitHub Releases'
$assets = @($ZipPath)
if ($IncludeApk) {
    if (Test-Path $Apk) { $assets += $Apk } else { Write-Warning 'examly.apk tidak ditemukan, dilewati.' }
}
& gh release create "v$Version" $assets --repo $Repo --title "v$Version" --notes $Notes
if ($LASTEXITCODE -ne 0) { throw 'Gagal membuat release di GitHub.' }

# ---------------------------------------------------------------- version.json
Step 'Menerbitkan version.json'
$ManifestPath = Join-Path $Root 'version.json'
Write-Utf8NoBom $ManifestPath $json

Push-Location $Root
try {
    git add version.json
    if ($LASTEXITCODE -ne 0) { throw 'git add gagal.' }
    git commit -m "Rilis v$Version" -- version.json
    if ($LASTEXITCODE -ne 0) { throw 'git commit gagal (cek git config user.name dan user.email).' }
    git push origin $Branch
    if ($LASTEXITCODE -ne 0) {
        throw "git push gagal. Release v$Version sudah dibuat; jalankan 'git push origin $Branch' manual setelah masalahnya diperbaiki."
    }
} finally { Pop-Location }

# ---------------------------------------------------------------- bersih-bersih lokal
$lines = @(
    '<?php',
    "defined('BASEPATH') OR exit('No direct script access allowed');",
    ('$config[''app_version''] = ''{0}'';' -f $Version)
)
Write-Utf8NoBom $VersionFile (($lines -join "`r`n") + "`r`n")

if ($hasSql -or $hasDel) {
    $archive = Join-Path $ToolsDir "releases\$Version"
    New-Item -ItemType Directory -Path $archive -Force | Out-Null
    if ($hasSql) { Move-Item $SqlSrc $archive -Force }
    if ($hasDel) { Move-Item $DelSrc $archive -Force }
    Write-Host "migrate.sql / delete.txt dipindahkan ke $archive"
}

Write-Host ''
Write-Host "SELESAI. Versi $Version sudah dirilis." -ForegroundColor Green
Write-Host "Rilis    : https://github.com/$Repo/releases/tag/v$Version"
Write-Host "Manifest : https://raw.githubusercontent.com/$Repo/$Branch/version.json"
Write-Host 'Catatan  : raw.githubusercontent.com bisa menyimpan cache sekitar 5 menit.'