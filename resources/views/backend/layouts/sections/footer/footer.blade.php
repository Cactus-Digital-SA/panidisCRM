@php
$containerFooter = (isset($configData['contentLayout']) && $configData['contentLayout'] === 'compact') ? 'container-xxl' : 'container-fluid';
@endphp

<!-- Footer-->
<footer class="content-footer footer bg-footer-theme">
  <div class="{{ $containerFooter }}">
    <div class="footer-container d-flex align-items-center justify-content-between py-2 flex-md-row flex-column">
      <div>
          COPYRIGHT © <script>document.write(new Date().getFullYear())
      </script>
          <a class="ms-25" href="https://www.cactusweb.gr/" target="_blank">Cactus</a>
          <span class="d-none d-sm-inline-block">All rights Reserved</span>
      </div>
      <div class="d-none d-lg-inline-block">
          Hand-crafted & Made with <i class="ti ti-heart" style="color: red;"></i>
          <span class="m-1 ">ver. {{config('version.string')}}</span>
      </div>
    </div>
  </div>
</footer>
<!--/ Footer-->
