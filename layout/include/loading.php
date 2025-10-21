<!-- Loader HTML -->
<div class="loader" id="page-loader" style="display: none;">
  <div class="justify-content-center jimu-primary-loading"></div>
</div>

<!-- Loader CSS -->
<style>
.loader {
  position: fixed;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  background: rgba(255, 255, 255, 0.7);
  z-index: 9999;
}

.jimu-primary-loading:before,
.jimu-primary-loading:after {
  position: absolute;
  top: 0;
  content: '';
}

.jimu-primary-loading:before {
  left: -19.992px;
}

.jimu-primary-loading:after {
  left: 19.992px;
  -webkit-animation-delay: 0.32s !important;
  animation-delay: 0.32s !important;
}

.jimu-primary-loading:before,
.jimu-primary-loading:after,
.jimu-primary-loading {
  background: #076fe5;
  -webkit-animation: loading-keys-app-loading 0.8s infinite ease-in-out;
  animation: loading-keys-app-loading 0.8s infinite ease-in-out;
  width: 13.6px;
  height: 32px;
}

.jimu-primary-loading {
  text-indent: -9999em;
  margin: auto;
  position: absolute;
  right: calc(50% - 6.8px);
  top: calc(50% - 16px);
  -webkit-animation-delay: 0.16s !important;
  animation-delay: 0.16s !important;
}

@-webkit-keyframes loading-keys-app-loading {
  0%, 80%, 100% {
    opacity: .75;
    box-shadow: 0 0 #076fe5;
    height: 32px;
  }
  40% {
    opacity: 1;
    box-shadow: 0 -8px #076fe5;
    height: 40px;
  }
}

@keyframes loading-keys-app-loading {
  0%, 80%, 100% {
    opacity: .75;
    box-shadow: 0 0 #076fe5;
    height: 32px;
  }
  40% {
    opacity: 1;
    box-shadow: 0 -8px #076fe5;
    height: 40px;
  }
}
</style>

<!-- Loader Script -->
<script>
function showPageLoader() {
  const loader = document.getElementById("page-loader");
  if (loader) loader.style.display = "block";
}

document.addEventListener("DOMContentLoaded", function () {
  // Loader khi submit form
  document.querySelectorAll("form").forEach(function (form) {
    form.addEventListener("submit", function () {
      showPageLoader();
    });
  });

  // Loader khi click vào <a>
  document.querySelectorAll("a[href]").forEach(function (a) {
    a.addEventListener("click", function (e) {
      const href = a.getAttribute("href");
      // Bỏ qua liên kết có href là javascript:history.back()
      if (
        href &&
        !href.startsWith("#") &&
        !a.hasAttribute("target") &&
        !href.startsWith("javascript:") &&
        href !== "javascript:history.back()"
      ) {
        e.preventDefault();
        showPageLoader();
        setTimeout(() => {
          window.location.href = href;
        }, 500); // delay 500ms để thấy spinner
      }
    });
  });
});
</script>