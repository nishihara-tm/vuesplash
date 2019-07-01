<template>
  <div class="photo-form" v-show="isShow">
    <h2 class="title">Submit a photo</h2>
    <form class="form">
      <input class="form__item" type="file">
      <div class="form__button">
        <button type="submit" class="button button--inverse">submit</button>
      </div>
    </form>
  </div>
</template>

<sctipt>
export default {
  props: {
    isShow: Boolean,
  }
}
</sctipt>
