<template>
  <main class="profileEdit">
    <ProfileCard />
    <div class="profileEdit__Edit">
      <div class="profileEdit__inner">
        <div class="profileEdit__title">Edit Profile</div>
        <Form v-model:is-processing="isProcessing" :handle-logic="editUser">
          <Input
            v-model:value="form.name"
            name="name"
            label="Name"
            type="text"
            placeholder="John Doe"
          />
          <Input
            v-model:value="form.email"
            name="email"
            label="Email"
            type="email"
            placeholder="your-email@example.com"
          />

          <Button type="submit" :loading="isProcessing">Edit Profile</Button>
        </Form>

        <!-- <img :src="imgDataUrl" /> -->
      </div>
    </div>
  </main>
</template>
<script setup>
import { ref, computed } from 'vue'
import ProfileCard from '@/components/my-profile/ProfileCard.vue'
import Input from '@/components/general/Input.vue'
import Button from '@/components/general/Button.vue'
import Form from '@/components/general/Form.vue'
import { useUserStore } from '@/stores/user.js'
import User from '../request/User'

const userStore = useUserStore()

const user = computed(() => userStore.user)

const isProcessing = ref(false)

const form = ref({
  name: user.value.name,
  email: user.value.email,
})

async function editUser() {
  await User.edit(user.value.id, form.value)
  await userStore.loginUser()
}
</script>
