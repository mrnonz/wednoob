<!DOCTYPE html>
<html lang="th">
    <head>
        <?php include_once 'template/taghead.php'; ?>
    </head>
    <body>
        <div id="fb-root"></div>
        <script>(function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.6&appId=401535140035063";
          fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
        </script>
        <?php include_once 'template/navbar.php'; ?>
        <!-- Content -->
        <div class="container">
            <?php /* include_once 'template/steamurl.php'; */ ?>
            <div class="panel panel-default">
                <div class="panel-body" style="padding: 15px;">