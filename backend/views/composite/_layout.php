<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?php echo Page::getFullTitle(); ?></title>
        <link href="/vendor/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,800,400italic,700,700italic' rel='stylesheet' type='text/css'>
        <link href="/res/style.css" rel="stylesheet" type="text/css">
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
        <script src="/res/tweet.js"></script>
    </head>
    <body>
        <header>
            <h1><?php echo Page::getHeader(); ?></h1>
        </header>
        <div id="content">
            <?php echo $content; ?>
        </div>
        <footer>
            HeardAtItu BETA |
            <a href="http://twitter.com/HeardAtItu" target="_blank">@HeardAtItu</a> |
            <a href="mailto:admin@deranged.dk">Contact</a> |
            <a href="http://atitu.dk">atITU.dk</a> <br>
            <a href="http://github.com/Bladtmand242/heardAtItu" target="_blank">Fork us on GitHub</a>
        </footer>
    </body>
</html>