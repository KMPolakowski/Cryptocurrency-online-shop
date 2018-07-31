<template>


<div class="container" align="center">
  <div class="buy-input">
  
  
    <h2>Buy Cryptocurrencies</h2>

    <div class="input-group mb-2">

  <select class="custom-select" id="inputGroupSelect01" v-model="transaction.selectedCrypto" placholder="cryptocurrency">
    <!-- <option selected>Choose...</option> -->
    <option v-for="cryptoName in cryptoNames" v-bind:key="cryptoName" :value="cryptoName"> {{ cryptoName[0] }} </option>

  </select>

  
    </div>
  

    <div class="input-group mb-2">
  

  
  <!-- Freaking ugly -->
  <div class="input-group-prepend">
    <span class="input-group-text">Quantity</span>
  </div>

        <input type="text" class="form-control" v-model="transaction.quantity" placeholder="quantity">
</div>
  <div class="input-group mb-2">

<div class="input-group-prepend">
    <span class="input-group-text">Amount in EUR</span>
  </div>
        <input type="text" class="form-control" v-model="newAmount" placeholder="amount" >
        
   


    </div>
    

  <a class="btn btn-danger" @click="pay()"> Pay with PayPal </a>
  
  

  </div>

<div class="container">

<form action="/charge" method="post" id="payment-form">
  <div class="form-row mb-2">
    <label for="card-element" >
      Credit or debit card
    </label>
    <div id="card-element" class="form-control">
      <!-- A Stripe Element will be inserted here. -->
    </div>

    <!-- Used to display form errors. -->
    <div id="card-errors" role="alert"></div>
  </div>

    <a class="btn btn-danger" @click="pay()"> Pay with Card </a>

</form>
</div>




</div>


</template>

<script>
export default {
  data() {
    return {
      cryptoNames: [],
      transaction: {
        selectedCrypto: {},
        quantity: null
      }
    };
  },

  created() {
    this.fetchCryptoNames();
  },

  methods: {
    fetchCryptoNames() {
      fetch("buy/crypto_names")
        .then(res => res.json())
        .then(res => {
          this.cryptoNames = res;
        })
        .catch(err => console.log(err));
    },

    pay() {
      if (
        !isNaN(this.transaction.quantity) ||
        this.transaction.selectedCrypto == null
      ) {
        this.transaction.selectedCrypto[1] = this.round(
          this.transaction.selectedCrypto[1]
        );

        this.$http.post("buy/pay", this.transaction).then(function(data) {
          if (isNaN(data.body)) {
            // console.log(data.body);
            window.location.href = data.body;
          } else {
            if (
              confirm(
                "The prices just changed. New Price: " +
                  this.round(data.body) +
                  "| New Amout: " +
                  this.round(data.body * this.transaction.quantity) +
                  "| Do you accept the transaction? "
              )
            ) {
              this.transaction.selectedCrypto[1] = this.round(data.body);
              console.log(this.transaction.selectedCrypto[1]);
              this.pay();
            }
          }
        });
      }
    },

    round(amount) {
      return Math.round(amount * 100) / 100;
    }
  },

  computed: {
    newAmount: function() {
      if (
        isNaN(this.transaction.quantity) ||
        typeof this.transaction.selectedCrypto[0] !== "string"
      ) {
        return "";
      } else {
        return this.round(
          this.transaction.quantity * this.transaction.selectedCrypto[1]
        );
      }
    }
  },

  mounted: function() {
    // Create a Stripe client.
    var stripe = Stripe("pk_test_8iH5Z251WD08KHDJuvtwe7FZ");

    // Create an instance of Elements.
    var elements = stripe.elements();

    // Custom styling can be passed to options when creating an Element.
    // (Note that this demo uses a wider set of styles than the guide below.)
    var style = {
      base: {
        color: "#32325d",
        lineHeight: "18px",
        fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
        fontSmoothing: "antialiased",
        fontSize: "16px",
        "::placeholder": {
          color: "#aab7c4"
        }
      },
      invalid: {
        color: "#fa755a",
        iconColor: "#fa755a"
      }
    };

    // Create an instance of the card Element.
    var card = elements.create("card", { style: style });

    // Add an instance of the card Element into the `card-element` <div>.
    card.mount("#card-element");

    // Handle real-time validation errors from the card Element.
    card.addEventListener("change", function(event) {
      var displayError = document.getElementById("card-errors");
      if (event.error) {
        displayError.textContent = event.error.message;
      } else {
        displayError.textContent = "";
      }
    });

    // Handle form submission.
    var form = document.getElementById("payment-form");
    form.addEventListener("submit", function(event) {
      event.preventDefault();

      stripe.createToken(card).then(function(result) {
        if (result.error) {
          // Inform the user if there was an error.
          var errorElement = document.getElementById("card-errors");
          errorElement.textContent = result.error.message;
        } else {
          // Send the token to your server.
          stripeTokenHandler(result.token);
        }
      });
    });
  }
};
</script>



