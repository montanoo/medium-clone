import { toast } from 'vue3-toastify'

export default (error, errorStore = undefined) => {
  if (error.response.status === 422) {
    if (errorStore) {
      errorStore.setErrors(error.response.data.errors)
      if (error.response.data.errors.error) {
        toast.error(error.response.data.errors.error, {
          autoClose: 1000,
          theme: 'dark',
          position: toast.POSITION.BOTTOM_RIGHT,
          hideProgressBar: true,
        })
      }
    }
  } else if (error.response.status === 401) {
    toast.error('You are not authorized to perform this action.', {
      autoClose: 1000,
      theme: 'dark',
      position: toast.POSITION.BOTTOM_RIGHT,
      hideProgressBar: true,
    })
  } else {
    toast.error('We hit a roadbump. Please try again.', {
      autoClose: 1000,
      theme: 'dark',
      position: toast.POSITION.BOTTOM_RIGHT,
      hideProgressBar: true,
    })
  }
}
