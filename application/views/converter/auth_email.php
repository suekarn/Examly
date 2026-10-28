<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <?= $title ?>
            <small><?= $sub_title ?></small>
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                
                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <i class="icon fa fa-check"></i> <?= $this->session->flashdata('success'); ?>
                    </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <i class="icon fa fa-ban"></i> <?= $this->session->flashdata('error'); ?>
                    </div>
                <?php endif; ?>

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-lock"></i> Verification Gate</h3>
                    </div>

                    <div class="box-body">
                        <!-- Form Send OTP -->
                        <?= form_open('converter/send_otp', ['class' => 'form-horizontal']); ?>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Email Tujuan</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <input type="email" name="email" class="form-control" value="<?= set_value('email', $user_email); ?>" required placeholder="Masukkan email...">
                                        <span class="input-group-btn">
                                            <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-paper-plane"></i> Kirim OTP</button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?= form_close(); ?>

                        <hr>

                        <!-- Form Verify OTP -->
                        <?= form_open('converter/verify_otp', ['class' => 'form-horizontal']); ?>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Kode OTP</label>
                                <div class="col-sm-9">
                                    <input type="text" name="otp_code" class="form-control input-lg text-center" placeholder="123456" maxlength="6" required style="letter-spacing: 8px; font-weight: bold;">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <button type="submit" class="btn btn-success btn-block btn-lg"><i class="fa fa-key"></i> Masuk Ke AI Converter</button>
                                </div>
                            </div>
                        <?= form_close(); ?>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>