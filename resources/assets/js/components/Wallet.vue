<template>
  <div class="container" align="center">
    <div class="card">
        <div class="card-header">Wallet</div>

        <div class="card-body">


            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Cryptocurrency</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Current Market Value in EUR </th>

                    </tr>
                </thead>
                <tbody>


                    <tr v-for="coin in walletData" v-bind:key="coin">
                        <th scope="row"> {{ coin[0] }} </th>
                        <td> {{ coin[1] }} </td>
                        <td>{{ round(coin[1] * coin[2]) }} </td>
                    </tr>

                    <tr>
                      <td> Your Api Token for (GET) /api/my_transactions: {{ api_token }} </td>
                    </tr>

                </tbody>

            </table>




        </div>

</div>

  </div>
</template>

<script>
export default {
  data() {
    return {
      walletData: [],
      api_token: null
    };
  },
  created() {
    this.fetchData();
  },

  mounted() {
    $("#background").height(0);

    let scrollHeight = Math.max(
      document.body.scrollHeight,
      document.documentElement.scrollHeight,
      document.body.offsetHeight,
      document.documentElement.offsetHeight,
      document.body.clientHeight,
      document.documentElement.clientHeight
    );

    $("#background").height(scrollHeight);
    window.dispatchEvent(new Event("resize"));
  },

  methods: {
    fetchData() {
      var a = this;

      this.$http.get("data/wallet").then(function(data) {
        data.body.forEach(function(coin, index) {
          window.console.log(coin);
          a.$http.get("api/coin/" + coin.coin_id).then(function(data) {
            a.walletData.push([
              data.body.data.name,
              coin.quantity,
              data.body.data.quotes.EUR.price
            ]);
          });
        });
      });

      this.$http.get("data/api_token").then(function(data) {
        this.api_token = data.body;
      });
    },
    round(amount) {
      return Math.round(amount * 100) / 100;
    }
  },
  computed: {
    orderedCryptoData: function() {
      return _.orderBy(this.cryptoData, "rank");
    }
  }
};
</script>
