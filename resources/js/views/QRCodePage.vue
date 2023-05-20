<template>
  <div>
    <h1 class="fs-2 text-capitalize text-center text-md-start">
      {{ $t('QR Code') }}: <span class="font-monospace">{{ $route.params.fullId }}</span>
    </h1>
    <div class="p-4 d-flex flex-column align-items-center justify-content-center">
      <a href :download="`qr-for-${$route.params.fullId}.png`" id="download-link">
        <QRCodeVue3
          :width="300"
          :height="300"
          :value="getURL()"
          :qrOptions="{ errorCorrectionLevel: 'H' }"
          :image="imageURL"
          :imageOptions="{ 
            hideBackgroundDots: true, 
            imageSize: 0.4, 
            margin: 5 
          }"
          :dotsOptions="{
            type: 'extra-rounded',
            color: '#000000',
          }"
          :cornersSquareOptions="{ type: 'extra-rounded', color: '#000000' }"
          fileExt="png"
          myclass="my-code-qr"
          imgclass="code-qr-img"
        />
      </a>
    </div>
  </div>
</template>

<script>
import QRCodeVue3 from "qrcode-vue3";

export default {
  name: 'QRCodePage',
  components: {
    QRCodeVue3
  },
  data() {
    return {
      imageURL: import.meta.env.VITE_LOGO_URL,
    }
  },
  methods: {
    getURL() {
      return `${import.meta.env.VITE_APP_URL}?check=${this.$route.params.fullId}`;
    },
    prepareDownload() {
      try{
        const link = document.getElementById('download-link');
        link.href = document.getElementsByClassName('code-qr-img')[0].src;
        link.click();
      } catch(err) {
        console.err('error', err);
      }
    },
  },
  mounted() {
    // setTimeout(() => {this.prepareDownload()},1000);
  }
}
</script>