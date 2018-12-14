                </div>
            </div>
        </div>
        <!-- Footer -->
        <footer>
            <div class="container">
                <div class="col-lg-4 visible-lg">
                    <div class="fb-page"
                         data-href="https://www.facebook.com/<?php echo $webinfo['webinfo_facebook']; ?>/"
                         data-small-header="true"
                         data-adapt-container-width="true"
                         data-hide-cover="false"
                         data-show-facepile="true"
                         data-show-posts="false">
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="col-lg-8" style="margin-bottom: 10px">
                        <h4 style="margin: 0px 0px 10px 0px;"><i class="fa fa-info-circle" aria-hidden="true"></i> เกี่ยวกับเรา</h4>
                        <?php echo $webinfo['webinfo_about']; ?>
                    </div>
                    <div class="col-lg-4" style="margin-bottom: 10px">
                        <h4 style="margin: 0px 0px 10px 0px;"><i class="fa fa-envelope" aria-hidden="true"></i> ติดต่อเรา</h4>
                        Facebook : <a href="https://www.facebook.com/<?php echo $webinfo['webinfo_facebook']; ?>/" target="_blank"><?php echo $webinfo['webinfo_facebook']; ?></a><br/>
                        Tel Number : <?php echo $webinfo['webinfo_phone']; ?><br/>
                        Line ID : <?php echo $webinfo['webinfo_line']; ?><br/>
                        Email : <?php echo $webinfo['webinfo_email']; ?>
                    </div>
                </div>
            </div>
        </footer>
        <!-- /Footer -->
        <script type="text/javascript" src="<?php echo $config['site_url'] ?>/js/bootstrap.min.js"></script>
        <script type="text/javascript" src='<?php echo $config['site_url'] ?>/js/jquery.datetimepicker.js'></script>
        <script type="text/javascript" src='<?php echo $config['site_url'] ?>/js/djdai.js'></script>
        <script type="text/javascript" src="https://www.tmtopup.com/topup/3rdTopup.php?uid=<?php echo $tmtopup['uid']; ?>"></script>
    </body>
</html>
