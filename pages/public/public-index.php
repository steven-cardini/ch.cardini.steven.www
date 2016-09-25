<?php
  //TODO: register client, start session (?)

  // include PHP configuration
  require('../../resources/php/config.php');

  // set content language
  I18n::initialize();

?>
<!DOCTYPE html>
<html lang="<?php echo I18n::getLang(); ?>">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Steven Cardini">

    <title>Steven Cardini</title>

    <!-- Bootstrap Core CSS -->
    <link href="resources/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="resources/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Kaushan+Script' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700' rel='stylesheet' type='text/css'>

    <!-- Galleria Theme CSS -->
    <link type="text/css" rel="stylesheet" href="resources/vendor/galleria/themes/classic/galleria.classic.css">
    
    <!-- Theme CSS -->
    <link href="resources/css/style.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body id="page-top" class="index">

    <!-- Navigation -->
    <nav id="mainNav" class="navbar navbar-default navbar-custom navbar-fixed-top">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span> Menu <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand page-scroll" href="#page-top">Steven Cardini</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li class="hidden">
                        <a href="#page-top"></a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#photos"><?php echo I18n::t("menu.photos"); ?></a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#about"><?php echo I18n::t("menu.about"); ?></a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#contact"><?php echo I18n::t("menu.contact"); ?></a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>

    <!-- Header -->
    <header>
        <div class="container">
            <div class="intro-text">
                <div class="intro-lead-in"><?php echo I18n::t("intro.title"); ?></div>
                <div class="intro-heading"><?php echo I18n::t("intro.text"); ?></div>
            </div>
        </div>
    </header>

    <!-- Photo Grid Section -->
    <section id="photos" class="bg-light-gray">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading"><?php echo I18n::t("menu.photos"); ?></h2>
                    <h3 class="section-subheading text-muted">Lorem ipsum dolor sit amet consectetur.</h3>
                </div>
            </div>
            <div class="row">
                
                <?php
                    $albumCatalog = PhotoAlbumCatalog::getInstance();
                    $photoAlbums = $albumCatalog->getAlbums();

                    foreach ($photoAlbums as $photoAlbum) {
                        $item = '<div class="col-md-4 col-sm-6 photo-item">
                                <a href="#photos-'.$photoAlbum->getId().'" class="photo-link" data-toggle="modal">
                                <div class="photo-hover">
                                    <div class="photo-hover-content">
                                        <i class="fa fa-plus fa-3x"></i>
                                    </div>
                                </div>';
                      if (!empty($photoAlbum->getFrontPhoto())) {
                        $item .= '<img src="'.$photoAlbum->getPhotoFolder(true).$photoAlbum->getFrontPhoto().'" class="img-responsive" alt="">';
                      } else {
                        $item .= '<img src="resources/img/portfolio/roundicons.png" class="img-responsive" alt="">';
                      }
                      $item .= '</a>
                                <div class="photo-caption">
                                    <h4>'.$photoAlbum->getTitle().'</h4>
                                    <p class="text-muted">'.$photoAlbum->getCaption().'</p>
                                </div>
                              </div>';
                        echo $item;
                    }
                ?>
            
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading"><?php echo I18n::t("menu.about"); ?></h2>
                    <h3 class="section-subheading text-muted"><?php echo I18n::t("about.subtitle"); ?></h3>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <ul class="timeline">
                        <li>
                            <div class="timeline-image">
                                <img class="img-circle img-responsive" src="resources/img/about/dna-163466_200.jpg" alt="">
                            </div>
                            <div class="timeline-panel">
                                <div class="timeline-heading">
                                    <h4>2007-2010</h4>
                                    <h4 class="subheading"><?php echo I18n::t("about.item1.title"); ?></h4>
                                </div>
                                <div class="timeline-body">
                                    <p class="text-muted"><?php echo I18n::t("about.item1.description"); ?></p>
                                </div>
                            </div>
                        </li>
                        <li class="timeline-inverted">
                            <div class="timeline-image">
                                <img class="img-circle img-responsive" src="resources/img/about/bacteria-67659_200.jpg" alt="">
                            </div>
                            <div class="timeline-panel">
                                <div class="timeline-heading">
                                    <h4>2010-2012</h4>
                                    <h4 class="subheading"><?php echo I18n::t("about.item2.title"); ?></h4>
                                </div>
                                <div class="timeline-body">
                                    <p class="text-muted"><?php echo I18n::t("about.item2.description"); ?></p>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="timeline-image">
                                <img class="img-circle img-responsive" src="resources/img/about/3.jpg" alt="">
                            </div>
                            <div class="timeline-panel">
                                <div class="timeline-heading">
                                    <h4>Since 2012</h4>
                                    <h4 class="subheading"><?php echo I18n::t("about.item3.title"); ?></h4>
                                </div>
                                <div class="timeline-body">
                                    <p class="text-muted"><?php echo I18n::t("about.item3.description"); ?></p>
                                </div>
                            </div>
                        </li>
                        <li class="timeline-inverted">
                            <div class="timeline-image">
                                <img class="img-circle img-responsive" src="resources/img/about/matrix-356024_200.jpg" alt="">
                            </div>
                            <div class="timeline-panel">
                                <div class="timeline-heading">
                                    <h4>2013-2017</h4>
                                    <h4 class="subheading"><?php echo I18n::t("about.item4.title"); ?></h4>
                                </div>
                                <div class="timeline-body">
                                    <p class="text-muted"><?php echo I18n::t("about.item4.description"); ?></p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>


    <!-- Contact Section -->
    <section id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading"><?php echo I18n::t("menu.contact"); ?></h2>
                    <h3 class="section-subheading text-muted">Lorem ipsum dolor sit amet consectetur.</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <form name="sentMessage" id="contactForm" novalidate>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Your Name *" id="name" required data-validation-required-message="Please enter your name.">
                                    <p class="help-block text-danger"></p>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control" placeholder="Your Email *" id="email" required data-validation-required-message="Please enter your email address.">
                                    <p class="help-block text-danger"></p>
                                </div>
                                <div class="form-group">
                                    <input type="tel" class="form-control" placeholder="Your Phone *" id="phone" required data-validation-required-message="Please enter your phone number.">
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <textarea class="form-control" placeholder="Your Message *" id="message" required data-validation-required-message="Please enter a message."></textarea>
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-lg-12 text-center">
                                <div id="success"></div>
                                <button type="submit" class="btn btn-xl">Send Message</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <span class="copyright">Copyright &copy; 2016 Steven Cardini</span>
                </div>
                <div class="col-md-4">
                    <ul class="list-inline social-buttons">
                        <li><a href="#"><i class="fa fa-twitter"></i></a>
                        </li>
                        <li><a href="#"><i class="fa fa-facebook"></i></a>
                        </li>
                        <li><a href="#"><i class="fa fa-linkedin"></i></a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <ul class="list-inline quicklinks">
                        <li>
                          <a href="#">Impressum</a>
                        </li>
                        <li>|</li>
                        <li>
                          <?php $langInfo = I18n::getLangSwitchInfo(); ?>
                          <a class="switch-language" href="<?php echo $langInfo["url"]; ?>"><?php echo $langInfo["lang"]; ?></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <!-- Photo Modals -->

    <?php
        foreach ($photoAlbums as $photoAlbum) {
           
          $item = 
          '<div id="photos-'.$photoAlbum->getId().'" class="modal fade modal-photo" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <!-- Wrapper for slides -->
                <div class="galleria">';

    $item .= '</div></div></div></div>';
            echo $item;
        }
    ?>

    <!-- jQuery -->
    <script src="resources/vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="resources/vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="resources/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Galleria JavaScript -->
    <script src="resources/vendor/galleria/galleria-1.4.2.js"></script>

    <!-- Contact Form JavaScript -->
    <script src="resources/js/jqBootstrapValidation.js"></script>
    <script src="resources/js/contact_me.js"></script>

    <!-- Theme JavaScript -->
    <script src="resources/js/agency.min.js"></script>

    <!-- Custom JavaScript -->
    <script src="resources/js/custom.js"></script>
    
</body>

</html>