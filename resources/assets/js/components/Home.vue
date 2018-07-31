<template>

    <div class ="container" id="dashboard-wrapper">


    <table class="table-striped w-100 p-3">
                <thead class="thead-light">
                    <tr>
                        <th scope="col"> Name </th>

                        <th scope="col"> Symbol </th>

                    </tr>
                </thead>
                <tbody>


                    <tr v-for="item in orderedCryptoData" v-bind:key="item.rank" @click="showMore(item.rank)">
                        <td> {{item.name}}</td>
                        <td> {{item.symbol}}  </td>
                    </tr>


                </tbody>

    </table>


</div>

</template>

<script>
export default {
  data() {
    return {
      cryptoData: {},
      selectedCoin: {}
    };
  },
  created() {
    this.fetchData();
  },

  methods: {
    fetchData() {
      //Gets data from coinmarketcap API through my own API

      this.$http.get("api/dashboard").then(function(data) {
        this.cryptoData = data.body.data;
        window.console.log(this.orderedCryptoData);
      });
    },
    showMore(index) {
      this.selectedCoin = index;

      this.$modal.show(
        {
          template: `
    <table class="table-striped w-100 p-3">
               
                <tbody  v-for="item in coins" v-if="item.rank == index" v-bind:key="item.rank">
                  
                        <tr>
                        <td> # </td>   
                        <td> {{ item.rank }} </td>               
                        </tr>

                        <tr>
                        <td scope="row"> Name </td>  
                        <td> <img v-bind:src = "'https://s2.coinmarketcap.com/static/img/coins/16x16/' + item.id + '.png'"> {{item.name}} </td>
                        </tr>

                        <tr>
                        <td scope="row"> Market Cap </td>  

                        <td>{{item.quotes.EUR.market_cap}} </td>
                        </tr>

                        <tr>
                        <td scope="row"> Price in EUR </td>  
                        <td>{{item.quotes.EUR.price}} </td>
                        </tr>

                        <tr>
                        <td scope="row"> 24h Volume </td>  
                        <td>{{item.quotes.EUR.volume_24h}} </td>
                        </tr>

                        <tr>
                        <td scope="row"> Circulating Supply </td>  
                        <td>{{item.circulating_supply}} </td>
                        </tr>

                        <tr>
                        <td scope="row"> percent_change_24h </td>  
                        <td v-if="item.quotes.EUR.percent_change_24h >= 0" style="color: green">{{item.quotes.EUR.percent_change_24h}} % </td>
                        <td v-else style ="color: red">{{item.quotes.EUR.percent_change_24h}} % </td>
                        </tr>

                        <tr>
                        <td scope="row"> 7d graph </td>  
                        <td> <img v-bind:src= "'https://s2.coinmarketcap.com/generated/sparklines/web/7d/usd/' + item.id +'.png'"> </td>
                        </tr>

                </tbody>

    </table>
  `,
          props: ["coins", "index"]
        },
        {
          coins: this.orderedCryptoData,
          index: index
        },
        {
          height: "auto"
        },
        {
          "before-close": event => {
            console.log("this will be called before the modal closes");
          }
        }
      );
    }
  },
  computed: {
    orderedCryptoData: function() {
      return _.orderBy(this.cryptoData, "rank");
    }
  }
};
</script>



