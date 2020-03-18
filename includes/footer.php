</section>
<footer class="fixed">
    <p class="copyright">Jorge G. Cano. Proyecto Final de Grado 2019 &copy; </p>
  </footer>
  </body>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <?php
  $url = $_SERVER["REQUEST_URI"];
  $parts = explode("/", $url);
  if (end($parts) == 'index.php'){
      echo "<script src='js/app.js'></script>";
  }
  else
  {
    echo "<script src='../js/app.js'></script>";
  }
  ?>
  <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>
</html>
