<template>
    <div class="form-group row">
        <div class="col-sm-1">
            <button
                class="btn btn-success btn-sm"
                type="button"
                @click="addDetailRow"
            >
                A
            </button>
            <button
                v-if="(detailKey !== 0)"
                class="btn btn-danger btn-sm"
                type="button"
                @click="deleteDescriptionRow(detailProduct.$model)"
            >
                X
            </button>
        </div>
        <div class="col-sm-5">
            <input
                type="text"
                class="form-control form-control-sm"
                v-model.trim="detailProduct.description.$model"
                :class="{'is-invalid': detailProduct.description.$error}"
            >
            <div class="invalid-feedback" v-if="!detailProduct.description.required">
                Field is required
            </div>
        </div>
        <div class="col-sm-2">
            <input
                type="text"
                class="form-control form-control-sm"
                :class="{'is-invalid': detailProduct.quantity.$error}"
                v-model.trim="detailProduct.quantity.$model"
            >
            <div class="invalid-feedback" v-if="!detailProduct.quantity.required">
                Field is required
            </div>
            <div class="invalid-feedback" v-if="!detailProduct.quantity.numeric">
                Field must be numeric
            </div>
        </div>
        <div class="col-sm-2">
            <input
                type="text"
                class="form-control form-control-sm"
                v-model.trim="detailProduct.rate.$model"
                :class="{'is-invalid': detailProduct.rate.$error}"
            >
            <div class="invalid-feedback" v-if="!detailProduct.rate.required">
                Field is required
            </div>
            <div class="invalid-feedback" v-if="!detailProduct.rate.numeric">
                Field must be numeric
            </div>
        </div>
        <div class="col-sm-2">
            <input
                type="text"
                class="form-control form-control-sm"
                v-model="amount"
                readonly
            >
        </div>
    </div>
</template>

<script>
   export default {
     props: {
       detail: {
         type: Object
       },
       detailKey: {
         type: Number
       }
     },
     data() {
       return {
         detailProduct: this.detail,
       }
     },
     methods: {
       addDetailRow() {
         this.$emit('addDetailRow')
       },
       deleteDescriptionRow(detail) {
         this.$emit('deleteDescriptionRow', { ...detail })
       },
     },
     computed: {
       amount: function() {
         const amount = this.detailProduct.$model.quantity * this.detailProduct.$model.rate;
         this.detailProduct.$model.amount = amount;
         return amount;
       }
     },
   }
</script>
