<script setup>
import SocialIcons from '@/components/socialIcons/SocialIcons.vue'
</script>

<template>
  <footer class="mainFooter">
    <div class="mainFooter__inner">
      <div class="mainFooter__column">
        <h3 class="mainFooterColumn__title">Contact the Publisher</h3>
        <a href="#" class="mainFooterColumn__tag">mike@runo.com</a>
        <a href="#" class="mainFooterColumn__tag">+944 450 904 505</a>
      </div>
      <div class="mainFooter__column">
        <h3 class="mainFooterColumn__title">Explorate</h3>
        <a href="#" class="mainFooterColumn__tag">About</a>
        <a href="#" class="mainFooterColumn__tag">Partners</a>
        <a href="#" class="mainFooterColumn__tag">Job Opportunities</a>
        <a href="#" class="mainFooterColumn__tag">Advertise</a>
        <a href="#" class="mainFooterColumn__tag">Membership</a>
      </div>
      <div class="mainFooter__column">
        <h3 class="mainFooterColumn__title">Headquarter</h3>
        <p>191 Middleville Road, NY 1001, Sydney Australia</p>
      </div>
      <div class="mainFooter__column">
        <h3 class="mainFooterColumn__title">Connections</h3>
        <SocialIcons class="showArticleFooter__icons-start"/>
      </div>
    </div>
    <div class="mainFooter__bottom">
      <div class="mainFooterBottom__inner">
        <h3 class="mainFooterBottom__title">2021 | RUNO Publisher Studio</h3>
        <p>Subscribe Now</p>
      </div>
    </div>
  </footer>
</template>
