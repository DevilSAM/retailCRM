<template>
  <div class="">

    <div class="mb-3">
      <label for="name" class="form-label">NAME</label>
      <input v-model="order['name']" type="text" name="name" class="form-control" id="name" aria-describedby="name" value="GHHG HGH tyR" placeholder="Иванов Иван Иванович">
    </div>

    <div class="mb-3">
      <label for="comment" class="form-label">COMMENT</label>
      <input v-model="order['comment']" type="text" name="comment" class="form-control" id="comment" aria-describedby="comment" placeholder="git link">
    </div>

    <div class="mb-3">
      <label for="code" class="form-label">ARTICLE</label>
      <input v-model="order['code']" type="text" name="code" class="form-control" id="code" aria-describedby="code" value="07160319-1139" placeholder="07160319-1139">
    </div>

    <div class="mb-3">
      <label for="brand" class="form-label">BRAND</label>
      <input v-model="order['brand']" type="text" name="brand" class="form-control" id="brand" aria-describedby="brand" value="Leander" placeholder="Leandre">
    </div>

    <button :disabled="ordering" class="btn btn-primary" id="getOrder" @click.prevent="getOrder()">ORDER</button>
    <div class="spinner-border ml-3" role="status" v-if="ordering">
      <span class="sr-only">Получаем данные...</span>
    </div>

  </div>
</template>

<script>
export default {

  props: {},

  data() {
    let token = document.head.querySelector('meta[name="csrf-token"]');
    return {
      token: token.content,
      headers: {
        'X-Csrf-Token': token.content,
      },
      data: {
        '_csrf_token': token.content,
      },
      order: {"name":"", "brand":"", "code":"", "comment":""},
      brands: [],
      articuls: [],
      ordering: false,
    }
  },

  created() {},

  watch: {},

  methods: {

    // функция получает данные о искомом продукте по бренду и артикулу
    getOrder() {
      this.ordering = true
      // получаем товар по фильтру
      let getFiltered = `https://superposuda.retailcrm.ru/api/v5/store/products/?apiKey=QlnRWTTWw9lv3kjxy1A8byjUmBQedYqb`;
      axios.post("/buy", {"brand": this.order.brand, "code": this.order.code})
          .then(response => {
            console.log(response.data)
            this.ordering = false
            let prod_id = response.data.productId
            if (prod_id) {
              this.placeOrder(prod_id)
            }
          })
    },

    // функция для размещения заказа по ID продукта
    placeOrder(id) {
      this.ordering = true
      let fio = this.order.name.split(' ')
      axios.post('/ordering',
          {
                  "order_id": id,
                  "first_name": fio[1],
                  "last_name": fio[0],
                  "patronymic": fio[2],
                  "comment": this.order.comment
          })
          .then(response => {
              this.ordering = false
              console.log(response)
          });
    }

  }

}
</script>
