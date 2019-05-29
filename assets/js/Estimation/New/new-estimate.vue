<template>
    <div class="row">
        <form
            @submit.prevent="submitHandler"
            method="post"
            class="w-100"
        >
            <div class="form-group row">
                <div class="col-sm-6">
                    <label>Form Type</label>

                    <select
                        class="form-control form-control-sm"
                        :class="{'is-invalid': $v.order.formType.$error}"
                        v-model.trim="$v.order.formType.$model"
                    >
                        <option value="invoice">Invoice</option>
                        <option value="estimate">Estimate</option>
                    </select>
                    <div class="invalid-feedback" v-if="!$v.order.formType.required">
                        Field is required
                    </div>
                </div>
                <div class="col-sm-6">
                    <label>Date Order (*)</label>
                    <datepicker
                        v-model.trim="$v.order.date.$model"
                        :class="{'is-invalid': $v.order.date.$error}"
                        input-class="form-control form-control-sm"
                    ></datepicker>
                    <div class="invalid-feedback" v-if="!$v.order.date.required">
                        Field is required
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-6">
                    <label>Customer Name (*)</label>
                    <input
                        type="text"
                        class="form-control form-control-sm"
                        :class="{'is-invalid': $v.order.customer.name.$error}"
                        v-model.trim="$v.order.customer.name.$model"
                    >
                    <div class="invalid-feedback" v-if="!$v.order.customer.name.required">
                        Field is required
                    </div>
                </div>
                <div class="col-sm-6">
                    <label>Phone (*)</label>
                    <input
                        type="tel"
                        v-model.trim="$v.order.customer.phone.$model"
                        :class="{'is-invalid': $v.order.customer.phone.$error}"
                        class="form-control form-control-sm"
                    >
                    <div class="invalid-feedback" v-if="!$v.order.customer.phone.required">
                        Field is required
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-6">
                    <label>Email (*)</label>
                    <input
                        type="email"
                        class="form-control form-control-sm"
                        v-model.trim="$v.order.customer.email.$model"
                        :class="{'is-invalid': $v.order.customer.email.$error}"
                    >
                    <div class="invalid-feedback" v-if="!$v.order.customer.email.required">
                        Field is required
                    </div>
                    <div class="invalid-feedback" v-if="!$v.order.customer.email.email">
                        Field requires a valid email
                    </div>
                </div>
                <div class="col-sm-6">
                    <label>Address</label>
                    <input
                        type="text"
                        class="form-control form-control-sm"
                        v-model="order.customer.address"
                    >
                </div>
            </div>

            <hr>

            <div class="form-group row">
                <div class="col-sm-1">
                    <h6 style="width: 100%"></h6>
                </div>
                <div class="col-sm-4" v-if="getProducts().length > 0">
                    <h6 class="mb-0">Description</h6>
                </div>
                <div class="col-sm-6" v-if="getProducts().length === 0">
                    <h6 class="mb-0">Description</h6>
                </div>
                <div class="col-sm-2" v-if="getProducts().length > 0">
                    <h6 class="mb-0">Products</h6>
                </div>
                <div class="col-sm-1">
                    <h6 class="mb-0">Qty</h6>
                </div>
                <div class="col-sm-2">
                    <h6 class="mb-0">Rate</h6>
                </div>
                <div class="col-sm-2">
                    <h6 class="mb-0">Amount</h6>
                </div>
            </div>

            <hr>

            <container v-for="(detail, detailKey) in $v.order.detail.$each.$iter" :key="detail.id">
                <detail-row
                    :key="detail.id"
                    :detail="detail"
                    :products="getProducts()"
                    :detailKey="Number(detailKey)"
                    @addDetailRow="addDetailRow"
                    @deleteDescriptionRow="deleteDescriptionRow"
                ></detail-row>
            </container>

            <hr>

            <div class="form-row">

                <div class="form-group col-md-9">
                    <h6 class="mb-0">Notes</h6>
                    <textarea
                        class="form-control"
                        placeholder="Notes..."
                        v-model.trim="$v.order.description.$model"
                    ></textarea>
                </div>
                <div class="form-group col-md-3">
                    <div class="form-group">
                        <h6 class="mb-0">Subtotal</h6>
                        <input
                            type="text"
                            class="form-control form-control-sm"
                            :value="getSubTotal()"
                            readonly
                        >
                    </div>

                    <div class="form-group">
                        <h6 class="mb-0">Deposit</h6>
                        <input
                            type="text"
                            class="form-control form-control-sm"
                            v-model.trim="$v.order.deposit.$model"
                            :class="{'is-invalid': $v.order.deposit.$error}"
                        >
                        <div class="invalid-feedback" v-if="!$v.order.deposit.required">
                            Field is required
                        </div>
                    </div>

                    <div class="form-group">
                        <h6 class="mb-0">Extras</h6>
                        <input
                            type="text"
                            class="form-control form-control-sm"
                            v-model.trim="$v.order.extra.$model"
                            :class="{'is-invalid': $v.order.extra.$error}"
                        >
                        <div class="invalid-feedback" v-if="!$v.order.extra.required">
                            Field is required
                        </div>
                    </div>

                    <div class="form-group">
                        <h6 class="mb-0">Total</h6>
                        <input
                            type="text"
                            class="form-control form-control-sm"
                            :value="getTotal()"
                            readonly
                        >
                    </div>

                    <div class="form-group">
                        <h6 class="mb-0">Balance Due</h6>
                        <input
                            type="text"
                            class="form-control form-control-sm"
                            :value="getBalanceDue()"
                            readonly
                        >
                    </div>
                </div>

            </div>

            <hr>

            <div class="form-group row">
                <div class="col-sm-12">
                    <button
                        :disabled="this.sending"
                        type="button"
                        class="btn btn-info btn-block"
                        v-on:click="submitPreview"
                    >
                        Preview
                    </button>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-12">
                    <button
                        :disabled="this.sending"
                        type="submit"
                        class="btn btn-success btn-block"
                    >
                        Save
                    </button>
                </div>
            </div>

        </form>
    </div>
</template>

<script>
  import uuid from 'uuid/v4';
  import _ from 'lodash';
  import axios from 'axios';
  import Datepicker from 'vuejs-datepicker';
  import DetailRow from './detail-row';
  import { required, email, numeric } from 'vuelidate/lib/validators';

  const detailOrderModel = {
    id: uuid(),
    description: null,
    product: null,
    quantity: 0,
    rate: 0,
    amount: 0
  };

  const orderModel = {
    id: uuid(),
    date: new Date(),
    deposit: 0,
    extra: 0,
    formType: null,
    description: '',
    customer: {
      name: null,
      email: null,
      phone: null,
      address: null,
    },
    detail: [
      detailOrderModel
    ]
  };

   export default {
     props: {
       pdfRoute: {
         type: String
       },
       products: {
         type: String
       },
     },
     components: {
       Datepicker, DetailRow,
     },
     data() {
       return {
         sending: false,
         order: _.cloneDeep(orderModel),
       }
     },
     methods: {
       getProducts() {
         console.log(JSON.parse(this.products))
         console.log(JSON.parse(this.products).length)
         return JSON.parse(this.products);
       },
       toggleSending() {
         this.sending = !this.sending;
       },
       addDetailRow() {
         const detailOrder = _.clone(detailOrderModel);
         detailOrder.id = uuid();
         this.order.detail.push(detailOrder);
       },
       deleteDescriptionRow({ id }) {
         this.order.detail = this.order.detail.filter(detail => detail.id !== id);
       },
       resetForm() {
         this.order = _.cloneDeep(orderModel);
         this.$v.order.$reset();
       },
       submitHandler() {
         this.$v.$touch();
         if (this.$v.$invalid === false) {
           this.toggleSending();
            axios.post(window.location.href, this.order).then(response =>
              window.location.href = `${this.pdfRoute}/pdf?id=${response.data.id}`
            ).then(() => (
              this.toggleSending(),
              this.resetForm()
            ));
         }
       },
       submitPreview() {
         this.$v.$touch()
         if (this.$v.$invalid === false) {
           this.toggleSending();
           axios.post(window.location.href + '?preview=true', this.order, {
             responseType: 'arraybuffer',
             headers: {
               'Content-Type': 'application/json',
               'Accept': 'application/pdf'
             }
           }).then((response) => {
             this.toggleSending();
             const url = window.URL.createObjectURL(new Blob([response.data]));
             const link = document.createElement('a');
             link.href = url;
             link.setAttribute('download', 'preview.pdf'); //or any other extension
             document.body.appendChild(link);
             link.click();
           });
         }
       },
       getSubTotal() {
         return this.order.detail
           .reduce((accumulator, detail) => Number(accumulator) + Number(detail.amount), 0);
       },
       getTotal() {
         return Number(this.getSubTotal()) + Number(this.order.extra);
       },
       getBalanceDue() {
         return Number(this.getTotal()) - Number(this.order.deposit);
       }
     },
     validations: {
       order: {
         id: {
           required
         },
         customer: {
           name: {
             required
           },
           email: {
             required, email
           },
           phone: {
             required
           },
         },
         description: {},
         formType: {
           required
         },
         date: {
           required
         },
         deposit: {
           required, numeric
         },
         extra: {
           required, numeric
         },
         detail: {
           $each: {
             product: {},
             description: {
               required
             },
             quantity: {
               required, numeric
             },
             rate: {
               required
             },
             amount: {
               required, numeric
             }
           }
         }
       }
     }
   }
</script>
