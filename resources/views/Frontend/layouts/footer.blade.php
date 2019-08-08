<footer class="footer-area section-padding-80-0">
        <!-- Main Footer Area -->
        <div class="main-footer-area">
            <div class="container">
                <div class="row align-items-baseline justify-content-between">
                    <!-- Single Footer Widget Area -->
                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="single-footer-widget mb-80">
                            <!-- Footer Logo -->
                            <a href="#" class="footer-logo"><img src="blog/img/core-img/logo2.png" alt=""></a>

                            <h4>+12-34-567-890</h4>
                            <span>xxx@gmail.com</span>
                            <span>XX, XXX, XXX, Ho Chi Minh, VN</span>
                        </div>
                    </div>

                    <!-- Single Footer Widget Area -->
                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="single-footer-widget mb-80">
                            <!-- Widget Title -->
                            <h5 class="widget-title">Our Blog</h5>

                            <!-- Single Blog Area -->
                            <div class="latest-blog-area">
                                <a href="#" class="post-title">Freelance Design Tricks How</a>
                                <span class="post-date"><i class="fa fa-clock-o" aria-hidden="true"></i> Jan 02, 2019</span>
                            </div>
                        </div>
                    </div>

                    <!-- Single Footer Widget Area -->
                    <div class="col-12 col-sm-4 col-lg-2">
                        <div class="single-footer-widget mb-80">
                            <!-- Widget Title -->
                            <h5 class="widget-title">Pages</h5>

                            <!-- Footer Nav -->
                            <ul class="footer-nav">
                                <li><a href="/"><i class="fa fa-caret-right" aria-hidden="true"></i> Home</a></li>
                                <li><a href="/hotels.html"><i class="fa fa-caret-right" aria-hidden="true"></i>Our Hotels</a></li>
                                <li><a href="/about.html"><i class="fa fa-caret-right" aria-hidden="true"></i>About Us</a></li>
                                <li><a href="/contact.html"><i class="fa fa-caret-right" aria-hidden="true"></i>Contact</a></li>
                                <li><a href="/policies.html"><i class="fa fa-caret-right" aria-hidden="true"></i>Policies</a></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Single Footer Widget Area -->
                    <div class="col-12 col-sm-8 col-lg-4">
                        <div class="single-footer-widget mb-80">
                            <!-- Widget Title -->
                            <h5 class="widget-title">Subscribe Newsletter</h5>
                            <span>Subscribe our newsletter gor get notification about new updates.</span>

                            <!-- Newsletter Form -->
                            <form action="#" class="nl-form">
                                <input type="email" class="form-control" placeholder="Enter your email...">
                                <button type="submit"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Copywrite Area -->
        <div class="container">
            <div class="copywrite-content">
                <div class="row align-items-center">
                    <div class="col-12 col-md-8">
                        <!-- Copywrite Text -->
                        <div class="copywrite-text">
                            <p>Copyright &copy;<script>document.write(new Date().getFullYear());</script> | All rights reserved</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- Login Modal -->
  <div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4>Login or Register</h4>
          <form action="#" method="post" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <table class="table">
                <tr>
                    <td><label for="">Username or email<span class="text-danger">*</span></label></td>
                    <td><input type="text" placeholder="Username or email" name="username"></td>
                </tr>
                <tr>
                    <td><label for="">Password<span class="text-danger">*</span></label></td>
                    <td><input type="password" placeholder="Password" name="password"></td>
                </tr>
                <tr>
                    <td colspan="2"><button class="btn roberto-btn" type="submit">Login</button></td>
                </tr>
                <tr>
                    <td colspan="2">Don't have an account?&nbsp <a href="#"><span class="text-danger">Register now!</span></a></td>
                </tr>
            </table>
          </form>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div>
