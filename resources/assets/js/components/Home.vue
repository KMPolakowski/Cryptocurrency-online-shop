<template>

    <div class ="container" id="dashboard-wrapper" >

      

<!-- <h3> Requesting each 100 coins limited to 60 times per minute, due to CoinMarketCap API restrictions. </h3> -->
<nav aria-label="Page navigation example" class="ml-auto mt-1 CoinPagination">

  <select class="custom-select" id="currencySwitcher" v-model="selectedCurrency" @change="updateCoins()" placeholder="cryptocurrency">
    <option v-for="currency in currencies" v-bind:key="currency" :value="currency"> {{ currency }} </option>

  </select>

  <select class="custom-select" id="languageSwitcher" v-model="selectedLang">
    <option v-for="language in languages" v-bind:key="language" :value="language"> {{ language[0] }} </option>
  </select>
      <ul class="pagination" v-if="showMenu">

        <li class="page-item"><a class="page-link" href="#" @click="fetchData(pagination.prev_page)"> < </a></li>

        <li class="page-item disabled"><a class="page-link text-dark" href="#">Page {{ pagination.current_page }} of {{ pagination.last_page }}</a></li>
    
        <li  class="page-item"><a class="page-link" href="#" @click="fetchData(pagination.next_page)">></a></li>

        <li  class="page-item"><a class="page-link" href="#" @click="fetchAll()"> View all </a></li>

              
      </ul>

      <ul class="pagination" v-else>

                <li class="page-item"><a class="page-link" href="#" @click="fetchData(1)"> Back to Top 100 </a></li>

      </ul>

      
    </nav>

    

    <table class="table-dark w-100 p-3" >
                <thead class="thead">
                    <tr>
                        <th scope="col"> {{this.text.Name[selectedLang[1]]}} </th>

                        <th scope="col"> {{this.text.Symbol[selectedLang[1]]}} </th>

                    </tr>
                </thead>
                <tbody>


                    <tr v-for="item in orderedCryptoData"  v-bind:key="item.rank" style="cursor: pointer" @click="showMore(item.rank)">
                        <td> {{item.name}}</td>
                        <td> {{item.symbol}}  </td>
                    </tr>


                </tbody>

    </table>


<nav aria-label="Page navigation example" class="ml-auto mt-5 CoinPagination">
      <ul  class="pagination" v-if="showMenu">

        <li class="page-item"><a class="page-link" href="#" @click="fetchData(pagination.prev_page)"> < </a></li>

        <li class="page-item disabled"><a class="page-link text-dark" href="#">Page {{ pagination.current_page }} of {{ pagination.last_page }}</a></li>
    
        <li  class="page-item"><a class="page-link" href="#" @click="fetchData(pagination.next_page)">></a></li>

        <li  class="page-item"><a class="page-link" href="#" @click="fetchAll()"> View all </a></li>

        

              
      </ul>

      <ul class="pagination" v-else>

                <li class="page-item"><a class="page-link" href="#" @click="fetchData(1)"> Back to Top 100 </a></li>

      </ul>

      
    </nav>




</div>

</template>

<script>
export default {
  data() {
    return {
      cryptoData: [],
      selectedCoin: {},
      pagination: {
        current_page: 1,
        last_page: 18,
        next_page: 2,
        prev_page: 18
      },
      selectedCurrency: "EUR",
      showMenu: true,
      languages: [["English", 0], ["Deutsch", 1], ["Polski", 2]],
      text: {
        Name: ["Name", "Name", "Nazwa"],
        Symbol: ["Symbol", "Abkürzung", "Skrót"],
        MarketCap: ["Market Cap", "Marktkap.", "Kap. rynku"],
        Price: ["Price", "Preis", "Cena"],
        "Volume(24h)": ["Volume(24h)", "Volumen(24h)", "Objętość (24godz.)"],
        CirculatingSupply: [
          "Circulating Supply(24h)",
          "Umlaufversorgung",
          "Podaż rynkowa"
        ],
        "Change(24h)": ["Change(24H)", "Änderung(24h)", "Zmiana(24godz.)"],
        "7dgraph": ["7d graph", "7t Graph", "wykres 7-dniowy"]
      },

      selectedLang: ["English", 0],
      dataHasLoaded: false,
      currencies: [
        "USD",
        "PLN",
        "EUR",
        "AUD",
        "BRL",
        "CAD",
        "CHF",
        "CLP",
        "CNY",
        "CZK",
        "DKK",
        "GBP",
        "HKD",
        "HUF",
        "IDR",
        "ILS",
        "INR",
        "JPY",
        "KRW",
        "MXN",
        "MYR",
        "NOK",
        "NZD",
        "PHP",
        "PKR",
        "RUB",
        "SEK",
        "SGD",
        "THB",
        "TRY",
        "TWD",
        "ZAR"
      ]
    };
  },
  created() {
    this.fetchData(this.pagination.current_page);
    // window.console.log(this.pagination.current_page);
  },
  // updated: function() {
  //   this.$nextTick(function() {
  //     let scrollHeight = Math.max(
  //       document.body.scrollHeight,
  //       document.documentElement.scrollHeight,
  //       document.body.offsetHeight,
  //       document.documentElement.offsetHeight,
  //       document.body.clientHeight,
  //       document.documentElement.clientHeight
  //     );

  //     $("#background").height(scrollHeight);

  //     window.dispatchEvent(new Event("resize"));
  //   });
  // },

  methods: {
    fetchAll() {
      this.dataHasLoaded = false;
      var newCryptoData = [];

      window.console.log(this.cryptoData);

      for (let i = 1; i < 19; i++) {
        this.$http
          .get("api/dashboard/" + i + "/" + this.selectedCurrency)
          .then(function(data) {
            Object.keys(data.body.data).forEach(function(key) {
              newCryptoData.push(data.body.data[key]);
            });
          });
      }
      this.dataHasLoaded = true;
      // $("#background").height(document.body.scrollHeight);
      window.console.log(document.body.scrollHeight);
      // window.dispatchEvent(new Event("resize"));

      // window.console.log(newCryptoData);
      this.showMenu = false;
      this.cryptoData = newCryptoData;
    },

    updateCoins() {
      if (this.showMenu) {
        this.fetchData(this.pagination.current_page);
      } else {
        this.fetchAll();
      }
    },

    fetchData(page) {
      this.showMenu = true;

      this.dataHasLoaded = false;
      //Gets data from coinmarketcap API through my own API
      this.$http
        .get("api/dashboard/" + page + "/" + this.selectedCurrency)
        .then(function(data) {
          this.cryptoData = data.body.data;
          this.dataHasLoaded = true;
        })
        .then(function() {
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
        });

      // make Pagination

      if (page == 1) {
        this.pagination.current_page = page;
        this.pagination.prev_page = 18;
        this.pagination.next_page = page + 1;
      } else if (page == 18) {
        this.pagination.current_page = page;
        this.pagination.prev_page = page - 1;
        this.pagination.next_page = 1;
      } else {
        this.pagination.current_page = page;
        this.pagination.prev_page = page - 1;
        this.pagination.next_page = page + 1;
      }
    },
    showMore(index) {
      if (this.dataHasLoaded) {
        this.selectedCoin = index;

        this.$modal.show(
          {
            template: `
    <table class="table-light w-100 p-3 coinInfo">
               
                <tbody  v-for="item in coins" v-if="item.rank == index" v-bind:key="item.rank">
                  
                        <tr>
                        <td> # </td>   
                        <td> {{ item.rank }} </td>               
                        </tr>

                        <tr>
                        <td scope="row"> {{text.Name[selectedLang[1]]}} </td>  
                        <td> <img v-bind:src = "'https://s2.coinmarketcap.com/static/img/coins/16x16/' + item.id + '.png'"> {{item.name}} </td>
                        </tr>

                        <tr>
                        <td scope="row"> {{text.MarketCap[selectedLang[1]]}} </td>  

                        <td v-if="item.quotes[selectedCurrency].market_cap != null">{{item.quotes[selectedCurrency].market_cap.toLocaleString('de-DE', { style: 'currency', currency: selectedCurrency })}} </td>
                        <td v-else> ? </td>
                        </tr>

                        <tr>
                        <td scope="row"> {{text.Price[selectedLang[1]]}}  </td>  
                        <td v-if="item.quotes[selectedCurrency].price != null">{{item.quotes[selectedCurrency].price.toLocaleString('de-DE', { style: 'currency', currency: selectedCurrency })}} </td>
                        <td v-else> ? </td>
                        </tr>

                        <tr>
                        <td scope="row"> {{text['Volume(24h)'][selectedLang[1]]}} </td>  
                        <td v-if="item.quotes[selectedCurrency].volume_24h != null">{{item.quotes[selectedCurrency].volume_24h.toLocaleString('de-DE', { style: 'currency', currency: selectedCurrency })}} </td>
                        <td v-else> ? </td>
                        </tr>

                        <tr>
                        <td scope="row"> {{text.CirculatingSupply[selectedLang[1]]}} </td>  
                        <td v-if="item.circulating_supply !== null">{{item.circulating_supply.toLocaleString()}} </td>
                        </tr>

                        <tr>
                        <td scope="row"> {{text['Change(24h)'][selectedLang[1]]}} </td>  
                        <td v-if="item.quotes[selectedCurrency].percent_change_24h >= 0" style="color: green">{{item.quotes[selectedCurrency].percent_change_24h}} % </td>
                        <td v-else style ="color: red">{{item.quotes[selectedCurrency].percent_change_24h}} % </td>
                        </tr>

                        <tr>
                        <td scope="row"> {{text['7dgraph'][selectedLang[1]]}} </td>  
                        <td> <img v-bind:src= "'https://s2.coinmarketcap.com/generated/sparklines/web/7d/usd/' + item.id +'.png'"> </td>
                        </tr>

                </tbody>

    </table>
  `,
            props: [
              "coins",
              "index",
              "selectedCurrency",
              "text",
              "selectedLang"
            ]
          },
          {
            coins: this.orderedCryptoData,
            index: index,
            selectedCurrency: this.selectedCurrency,
            text: this.text,
            selectedLang: this.selectedLang
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
    }
  },
  computed: {
    orderedCryptoData: function() {
      return _.orderBy(this.cryptoData, "rank");
    }
  }
};
</script>



