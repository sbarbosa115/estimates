import Vue from 'vue';
import NewEstimate from './New/new-estimate';
import Vuelidate from 'vuelidate';

Vue.component('container', {
  render() {
    return this.$scopedSlots.default({})
  },
})

Vue.component('estimate', {
  props: {
    pdfRoute: {
      type: String
    },
    products: {
      type: String
    },
  },
  components: {
    NewEstimate,
  },
  template: '<new-estimate :pdf-route="pdfRoute" :products="products"></new-estimate>',
});

Vue.use(Vuelidate);

new Vue({
  el: '#app',
}).$mount('#app');


