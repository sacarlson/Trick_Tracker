<!DOCTYPE html>
<html lang="en">
<head>
  <title> Stellar Wallet </title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"> 
  <link rel="stylesheet" href="table.css"> 
  <link rel="stylesheet" href="tab.css"> 
  <script src="https://code.jquery.com/jquery-2.2.1.min.js" integrity="sha384-8C+3bW/ArbXinsJduAjm9O7WNnuOcO+Bok/VScRYikawtvz4ZPrpXtGfKIewM9dK" crossorigin="anonymous"></script>
  <script src="js/qrcode.js" integrity="sha384-hYj7cf8JCFyDuU90YJY9/TgSVpAtbrqW6oQliv63kjRTr4oyOTeITwmTfSI+UC0a" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
  <script src="js/aes.js" integrity="sha384-YkYpnhy3j3+zc3fQvzlbh4WGwDgt+06gsGsaApwM1O3IKIsKJk61C0Lr6YvbovUV" crossorigin="anonymous"></script>
 <script src="https://cdn.rawgit.com/stellar/bower-js-stellar-sdk/cd1ecc03cce645b6f7b12abc211b239d24b39388/stellar-sdk.min.js" integrity="sha384-bJ4QHMD70wZKJdwVRTtrMIxjtS1jSbv+VpWK+9iECEhzwDxzeRWYBKM3MjpBxxxu" crossorigin="anonymous"></script>
 
  <script src="js/transaction_toolsv0.4.0.js"></script>
  <script src="js/tablesort.js"></script>
  <script src="js/tablesort.number.js"></script>

   
</head>
<body>
  <div class="panel panel-primary">
    
    <div class="panel-heading">
      <div class="row">
        <div class="col-sm-4">          
          <span id="top_image_span"></span>
          <h5> Active AccountId: <span id="account_disp"></span></h5>
        </div> 
        <div class="col-sm-4">
          <span id="top_page_title_span"></span>
          <h3> Balance: <span id="bal_disp"></span> (XLM)</h3>         
        </div> 
        <div class="col-sm-4">
          <h3> Active Network: <span id="active_network"></span> </h3>
          <h3>TX Status: <span id="tx_status" ></span> </h3>                  
        </div>  
      </div>    
    </div>
  </div>

      <ul class="tabrow">
        <li class="active"><a data-toggle="tab"  href="#send"> <h4>Send</h4></a></li>     
        <li><a data-toggle="tab" href="#rec"> <h4> Receive </h4></a></li>
        <li><a data-toggle="tab" href="#trans"><h4>Transaction Hist</h4></a></li>
        <li><a data-toggle="tab" href="#assets"><h4>List Assets</h4></a></li>
        <li><a data-toggle="tab" href="#import"><h4>Import/Export Key</h4></a></li>
        <li><a data-toggle="tab" href="#trade"><h4>Trade</h4></a></li>
        <li><a data-toggle="tab" href="#adv"><h4>Advanced</h4></a></li>
      </ul>
     
  

<div class="tab-content">

<div id="send" class="tab-pane fade in active" >
  <h2>Send Funds</h2>
  <p>Send funds from you to another accountId</p> 
  <button type="button" class="btn btn-success btn-lg" id="send_payment" >Send_Payment</button>
  <label class="checkbox-inline"><input type="checkbox" value="" id="new_account" ><p>Create New Account</p></label>

 <br /> <br /> 
 
<div class="row">
  <div class="col-sm-6">
    <div class="form-group">
       <label for="amount">  Amount:</label>
       <input type="text" class="form-control" id="amount">
       <label for="destination">  Destination AccountId:</label>
       <div class="input-group">
         <input type="text" class="form-control" id="destination">
         <span class="input-group-btn">
           <button type="button" class="btn btn-info" id="fed_lookup" >Fed_Lookup</button>
         </span>
       </div>
       <label for="asset">  Asset:</label>
       <input type="text" class="form-control" id="asset">
       <label for="issuer">  Issuer:</label>
       <div class="input-group">
         <input type="text" class="form-control" id="issuer">
         <span class="input-group-btn">
           <button type="button" class="btn btn-info" id="fed_lookup_issuer" >Fed_Lookup</button>
         </span>
       </div>
       <label for="dest_seed">  Destination Seed (Optional):</label> 
       <input type="text" class="form-control" id="dest_seed">      
    </div>
  </div>
</div>  
   Memo: <input id="memo" size="30" />
  <select name="memo_mode" id="memo_mode" value='auto'>
    <option>auto</option>
    <option>memo.id</option>
    <option>memo.text</option>
  </select><br /> 
  <input id="email_funds" type="button" value="email_funds" />
  To:  <input id="email_address" type="text" size="25" value="sacarlson_2000@yahoo.com" /><br />
  <input id="gen_random_dest" type="button" value="gen_random_dest" /><br />
 
</div>

<div id="rec" class="tab-pane fade">
  <h2>Receive Funds</h2>
  <p>Info to provide a sender that will be sending funds to you </p>
  <h3>AccountId: <span id="account_disp2"></span></h3>
  <div id="qrcode2" style="width:200px; height:200px; margin-top:15px;"></div> 
  <p>Public addressId QR-code Compatible with Centaurus: </p><br />
</div>

<div id="trans" class="tab-pane fade">
  <h2>Transaction History</h2>
  <p>Recent Fund Tranfer Transaction History on this accountId (sortable)</p>
  <div class="table-responsive">
<! <table id="table" class="table table-hover table-striped table-bordered">
<table id="table" >
<thead>
<tr>
  <th>TX AccountID</th>
  <th>Tx type</th>
  <th>Asset Code</th>
  <th>Amount</th>
  <th>Balance</th>
  <th>Memo</th>
  <th class='sort-default'>Date/Time</th>
</tr>
</thead>
<tbody>
<tr>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
</tr>

</tbody>
</table>
</div>
</div>

<div id="assets" class="tab-pane fade">
  <h2>List Asset Holdings</h2>
  <p>List all Asset Issuer holdings held by this AccountId (sortable)</p>
  <div class="table-responsive">
<! <table id="table_asset" class="table table-hover table-striped table-bordered">
<table id="table_asset" >
<thead>
<tr>
  <th>Asset Code</th>
  <th>Issuer AccountID</th>
  <th>Balance</th>  
  <th>Limit</th> 
</tr>
</thead>
<tbody>
 <tr>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
 </tr>
</tbody>
</table>
</div>
</div>


<div id="import" class="tab-pane fade">
  <h2>Import/Export Keyset</h2>
  <p>Import or Export a stellar keyset from browser local or systsem file to be used in transactions</p>
  <h5> Save LocalStorage key sets to system file: <h5>  
  <input id="save_to_file" type="button" value="Export_to_file" /><br />
  <h5>Import backup from local system file to browser Local Storage key sets:</h5> <input type="file" id="file-input" /><br /> 
  <div class="col-sm-2">
    <div class="form-group">
      <br /><h5> Select a key set Nick Name that is now in browser Local Storage: <h5>
      <label for="select_seed">Select Seed</label>
      <SELECT  class="form-control"  id="select_seed">
        <Option class="placeholder" selected disabled value="">Select Seed</option>
      </SELECT>   
    </div> 
  </div>
  <div class="col-sm-6"> 
    <input id="save" type="button" value="save_seed" />
    <input id="restore" type="button" value="restore_seed" /> 
    <input id="delete_key" type="button" value="delete_seed_key" />  
    <input id="swap_seed_dest" type="button" value="swap_seed_dest" /><br />
    <h5>Seed Nick Name or public ID:</h5>
    <input id="seed_nick" class="form-control" size="60" value = "seed1" /><br /> 
    <h5>Encrypt/Decrypt Seed pass phrase:</h5>
    <input id="pass_phrase" class="form-control" size="60" /><br />
    <h5>Secret Seed:</h5>  
    <input id="seed" class="form-control"  />  
    <input id="decrypt_seed" type="button" value="decrypt_seed" />
    <input id="encrypt_seed" type="button" value="encrypt_seed" /><br />  
    <h5>AccountId:</h5>
    <input id="account" class="form-control" size="60" /><br />
    <h5> Raw secret seed QRcode </h5>
    <div id="qrcode" style="width:200px; height:200px; margin-top:15px;"></div> 
  </div>
  
</div>

<div id="adv" class="tab-pane fade">
  <h2> Advanced Transaction Functions</h2>
  <p> Advanced transactions include adding trustlines, TX signing of multi sig transactions and other advanced functions</p>
   <ul class="tabrow">
     <li class="active"> <a data-toggle="tab" href="#settings"><h4>Change Settings</h4></a></li> 
     <li><a data-toggle="tab" href="#addtrust"> <h4>Add Trustlines </h4></a></li>     
     <li><a data-toggle="tab" href="#sign"> <h4> Sign Tx </h4></a></li>
     <li><a data-toggle="tab" href="#merge"><h4>Merge Accounts</h4></a></li>
     <li><a data-toggle="tab" href="#account_opts"><h4>Account Options</h4></a></li>                     
  </ul>
  <div class="tab-content">

   
   <div id="settings" class="tab-pane fade in active" >
      <h2> Change Default Network and Page Settings</h2>
      <p> Change default network settings including default horizon/mss-server address pointing, mss-server/horizon modes, Live/Testnet/custom network pass phrase and page settings for top page URL of image and top page title. </p> 
      <p>Also refresh balance:</p> <input id="change_network" type="button" value="Submit Changes" /><br /><br />
<select name="network" id="network" value='testnet_default'>
  <option>testnet_default</option>
  <option>live_default</option> 
  <option>mss_server_testnet</option> 
  <option>mss_server_live</option>  
  <option>testnet</option>
  <option>live</option>
  <option>custom</option>
</select>
<input id="current_mode"  value="current_mode?" /><br />
 MSS-server socket Status: <span id="status"></span><br />
 Network Passphrase: <input id="net_passphrase" size="50" /><br />
 Horizon/mss_server URL: <input id="url" size="30" />
 Port: <input id="port" size="6" />  secure
<select name="secure" id="secure" value='true'>
  <option>true</option>
  <option>false</option>
</select><br />
 Default Asset_code: <input id="default_asset_code" size="16" value="FUNT" /><br />
 Default Issuer: <input id="default_issuer" size="65" value="GBUYUAI75XXWDZEKLY66CFYKQPET5JR4EENXZBUZ3YXZ7DS56Z4OKOFU" /><br />
 Enable Auto Trust Count: <input id="auto_trust" size="16" value="0" /><br />
 Top Image URL: <input id="top_image_url" size="50" /><br />
 Top Page Title: <input id="top_page_title" size="20" /><br />
 Page Background Color: <input id="background_color" size="12" value="#666" /><br />
 Page Background URL Image: <input id="background_img" size="30" value="images/pilar.gif" /><br />
 Page Text Color: <input id="text_color" size="12" value="#fff" /><br />
 MSS-server: <input id="open" type="button" value="Connect" />&nbsp;
  <input id="close" type="button" value="Disconnect" /><br />
  <p> Legacy Message Display: </p><br /> 
  <div style="width:800px;height:300px;line-height:3em;overflow:scroll;padding:10px;">
    <span id="message"></span>
  </div>            
</div>

   <div id="addtrust" class="tab-pane fade in " >
      <h2> Add Trustline</h2>
      <p> Add a TrustLine to this your account or Allow Trust from the issuer account </p>
      <p> Note: for Allow Trust you must have the issuer setup with Authorization required (0x1) and Authorization revocable (0x2) done in stellar lab</p>
      <div class="col-sm-6">
        <div class="form-group"> 
          <input id="add_trustline" type="button" value="Add_Trustline" /><br />
          <input id="allowTrust" type="button" value="Allow_Trust" /> 
          <label class="checkbox-inline"><input type="checkbox" value="" id="lock_account" ><p>Lock Account  </p></label> 
          <br /> Asset Code: <input  id="tasset" size="12" value = "" /><br />
          <label for="tissuer">  Trust Issuer:  or Trustor: if AllowTrust</label>
          <div class="input-group">
            <input type="text" class="form-control" id="tissuer" value = "" /> <br />
            <span class="input-group-btn">
              <button type="button" class="btn btn-info" id="fed_lookup_tissuer" >Fed_Lookup</button>
            </span>
          </div>
          
          Credit Limit: <br /> <input id="tlimit" size="12" value = "" /> Set to 0 to delete TrustLine <br />
        </div>          
      </div>           
   </div>
 
   <div id="sign" class="tab-pane fade in" >
      <h2>Sign Tx</h2>
      <p>Sign a multi sig base64 tx envelope</p> 
      <input id="sign_tx" type="button" value="sign_tx" />
      <input id="email_tx" type="button" value="email_tx" />
      <input id="send_tx" type="button" value="submit_tx" /><br />    
      <div class="form-group">
       <label for="comment">TX Envelope Base64:</label>
       <textarea class="form-control" rows="6" id="envelope_b64"></textarea>
      </div>
       
                  
   </div>


   <div id="merge" class="tab-pane fade in" >
      <h2>Merge Accounts</h2>
      <p>Merge all this accounts Lumen assets into a target accountId, must have no other asset trustlines</p> 
      <div class="col-sm-6">
        <div class="form-group"> 
          <input id="merge_accounts" type="button" value="merge_accounts" /><br />
          <label for="merge_dest">  Merge Destination AddressID:</label>
          <div class="input-group">
             <input  type="text" class="form-control" id="merge_dest"  value = "" />
             <span class="input-group-btn">
               <button type="button" class="btn btn-info" id="fed_lookup_merge_dest" >Fed_Lookup</button>
             </span>
          </div>
        </div>
      </div>            
   </div>

   

   <div id="account_opts" class="tab-pane fade in" >
      <h2>Account Settings Options</h2>
      <p>Advanced Account Settings including:Add signers, Account Signing Threshold Weight settings, Inflation destination, Home Domain Settings.  Caution be careful with threshold setting as you can lock yourself out of your own account</p>
    <div class="col-sm-6">
     <div class="form-group">
       
        <input id="add_signer" type="button" value="add_signer" /><br />
        <label for="signer">  Signer:</label> 
        <div class="input-group">
          <input type="text"  class="form-control" id="signer" />
          <span class="input-group-btn">
           <button type="button" class="btn btn-info" id="fed_lookup_signer" >Fed_Lookup</button>
          </span>
        </div>
        <label for="weight">  Weight:</label><br />
        <input  id="weight" size="3" value = "1" /><br /><br />
        <label for="inflation_dest">  Inflation Destination:</label>
        <div class="input-group">
          <input type="text"  class="form-control" id="inflation_dest" value = "GBL7AE2HGRNQSPWV56ZFLILXNT52QWSMOQGDBBXYOP7XKMQTCKVMX2ZL" />
          <span class="input-group-btn">
           <button type="button" class="btn btn-info" id="fed_lookup_inflation_dest" >Fed_Lookup</button>
          </span>
        </div>        
        <input id="set_inflation_dest" type="button" value="set_inflation_dest" /><br />
        <br />
        <input id="change_threshold" type="button" value="change_options" />
        Master_weight: <input id="master_weight" size="3" value = 1 /> 
        Threshold: <input id="threshold" size="3" value = 0 /><br />
        Home_domain: <input id="home_domain" size="40" value = "funtracker.site" /> <br /> 
     </div>
    </div>                  
   </div>

 </div>
</div>

<div id="trade" class="tab-pane fade">
  <h2> Trade Transaction Tools</h2>
  <p> Tools that allow trading of stellar assets for other assets</p>
   <ul class="tabrow">
     <li class="active" ><a data-toggle="tab" href="#trade_history"><h4>Trade History</h4></a></li>
     <li><a data-toggle="tab" href="#view_offer"><h4>View Offers</h4></a></li>
     <li><a data-toggle="tab" href="#view_orderbook"><h4>View OrderBook</h4></a></li>
     <li><a data-toggle="tab" href="#view_paths"><h4>View Paths</h4></a></li>
     <li><a data-toggle="tab" href="#create_offer"><h4>Create Offers</h4></a></li>                      
  </ul>
 <div class="tab-content">

   <div id="trade_history" class="tab-pane fade in active" >
      <h2>Asset Trade History</h2>
      <p>History of Asset Trade transactions on this AccountId (sortable)</p>
      <div style="overflow-x:auto;"> 
      <! <table id="table_trade_history" class="table table-hover table-striped table-bordered">
      <table id="table_trade_history" >
        <thead>
          <tr> 
            <th>Offer ID</th>         
            <th>Sold Asset</th>
            <th>Sold Issuer</th>
            <th>Sold Amount</th>
            <th>Bought Asset</th>
            <th>Bought Issuer</th> 
            <th>Bought Amount</th>
            <th>Date/Time</th> 
          </tr>
        </thead>
        <tbody>
          <tr>
            <td></td>
	    <td></td>
	    <td></td>
	    <td></td>
	    <td></td>
	    <td></td>
	    <td></td>
	    <td></td>
          </tr>
       </tbody>
     </table> 
     </div>            
   </div>

   <div id="view_offer" class="tab-pane fade in" >
      <h2>Open Order Offers</h2>
      <p>Presently open orders setup and running on this accountId (sortable)</p> 
      <! <table id="table_offers" class="table table-hover table-striped table-bordered">
      <table id="table_offers" class="table table-hover table-striped table-bordered">
        <thead>
          <tr>
            <th class='sort-default' >Offer ID</th>  
            <th>Selling Asset</th>
            <th>Selling Issuer</th>
            <th>Amount</th>
            <th>Price</th>
            <th>Buying Asset</th>
            <th>Buying Issuer</th>  
            <th>Cancel</th> 
          </tr>
        </thead>
        <tbody>
          <tr>
             <td></td> 
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
          </tr>
          </tr>
       </tbody>
     </table>             
   </div>

  <div id="view_paths" class="tab-pane fade in" >
      <h2>Paths Viewer</h2>
      <p>Search Paths of offers with a destination address for a destination asset pair at a set amount</p><br />
      <p>This uses present selected account as source address</p><br />
      Destination AddressID: <input id="paths_destination_addressID" type="text" size="65" value="" /><br />
      Destination Asset: <input id="paths_destination_asset" type="text" size="15" value="" /><br />
      Destination Asset Issuer: <input id="paths_destination_asset_issuer" type="text" size="65" value="" /><br />
      Destination Amount: <input id="paths_destination_amount" type="text" size="15" value="" /><br />
       <br /><input id="check_paths" type="button" value="Check_Paths" /><br /><br />

      <! <table id="table_offers" class="table table-hover table-striped table-bordered">
      <table id="table_paths" class="table table-hover table-striped table-bordered">
        <thead>
          <tr>           
            <th>Destination Asset</th>
            <th>Destination Issuer</th>
            <th data-sort-method='number' >Destination Amount</th>
            <th>Path Asset</th>
            <th>Path Issuer</th>    
            <th>Source Asset</th>
            <th>Source Issuer</th>
            <th data-sort-method='number' >Source Amount</th>
              
          </tr>
        </thead>
        <tbody>
          <tr>
             <td></td> 
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
          </tr>
       </tbody>
     </table>             
   </div>

   <div id="view_orderbook" class="tab-pane fade in" >
      <h2>Order Book Viewer</h2>
      <p>View orderbook summary of all offers with the assets selling and buying</p>
      <p>These are all prently open orders on the network</p><br />
      Sell Asset: <input id="orderbook_sell_asset" type="text" size="15" value="" /> :Counter Asset<br />
      Sell Issuer: <input id="orderbook_sell_issuer" type="text" size="65" value="" /><br />
      Buy Asset: <input id="orderbook_buy_asset" type="text" size="15" value="" /> :Base Asset<br />
      Buy Issuer: <input id="orderbook_buy_issuer" type="text" size="65" value="" /><br />
       <br /><input id="check_orderbook_ask_button" type="button" value="Check_OrderBook_Asks" />_____
       <input id="check_orderbook_bid_button" type="button" value="Check_OrderBook_Bids" /><br /><br />
       <br /><input id="better_bid_ask_button" type="button" value="Better_Bid_Ask" /> by: <input id="better_bid_ask" type="text" size="6" value="" /> Percent <br />
        <p>Better bid will setup an order that is +- precent more or less than your results seen in OrderBook table bellow</p>
        <p>Example if you set Better bid to +10% on an ask it will prepare to setup an order at a price 10% more than the price seen in Orderbook</p><br />
      <br />
      <! <table id="table_offers" class="table table-hover table-striped table-bordered">
      <table id="table_orderbook" class="table table-hover table-striped table-bordered">
        <thead>
          <tr>           
            <th data-sort-method='number' >Price</th>
            <th data-sort-method='number' >Price R</th>
            <th data-sort-method='number' >Amount (qty ask/bid)</th>                       
          </tr>
        </thead>
        <tbody>
          <tr>
             <td></td> 
             <td></td>
             <td></td>             
          </tr>
       </tbody>
     </table>             
   </div>

   <div id="create_offer" class="tab-pane fade in" >
      <h2>Create Offer</h2>
      <p>Create an order offer of an asset issuer set for another asset issuer set presently held and trusted by this accountId.
 To sell or buy native Lumens use XLM as Asset_code with no Asset_issuer (blank)</p> 
      <div class="col-sm-6">
        <div class="form-group"> 
          <input id="submit_offer" type="button" value="submit_offer" /><br />
          Selling Asset_code:<br /> <input id="selling_asset_code" size="14" value = "" /> <br />
          <label for="selling_asset_issuer">  Selling Asset Issuer:</label>
          <div class="input-group">
            <input type="text" class="form-control" id="selling_asset_issuer" value = "" /> 
            <span class="input-group-btn">
              <button type="button" class="btn btn-info" id="fed_lookup_selling_asset_issuer" >Fed_Lookup</button>
            </span>
          </div>
          Selling Amount:<br /> <input id="selling_amount" size="14" value = "" /> <br />
          Selling Price:<br /> <input id="selling_price" size="14" value = "" /> <br /> <br /> 
          Buying Asset_code:<br /> <input id="buying_asset_code" size="14" value = "" /> <br />
          <label for="buying_asset_issuer"> Buying Asset Issuer:</label>
          <div class="input-group">
             <input type="text" class="form-control" id="buying_asset_issuer" value = "" />
             <span class="input-group-btn">
               <button type="button" class="btn btn-info" id="fed_lookup_buying_asset_issuer" >Fed_Lookup</button>
             </span> 
          </div> 
          <br /> <br /><input id="cancel_offer" type="button" value="cancel_offer" /> 
          OfferID:  <input id="offerid" size="10" value = "" /> <br />           
        </div>
      </div>         
  </div>

</div>
</div>

</div>

</body>
</html>
