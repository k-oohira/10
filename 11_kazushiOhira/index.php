<?php
include("functions.php");

// DB接続
$pdo = db_con();

//データ登録SQL作成
$stmt = $pdo->prepare("SELECT * FROM br_latlng");
$status = $stmt->execute();

?>


<!doctype html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width">
<!--　LINK -->
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/sample.css">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
<!-- MAP -->
  <!-- <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script> -->
  <script  async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDHpq5C28abapUHxLHEwbK8MQt0ZMCXeK4&callback=initMap">
  </script>
  
 
 <!-- JS -->

  <link type="text/css" rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/cupertino/jquery-ui.min.css" />   
  <script type="text/javascript" src="js/jquery-2.1.3.min.js"></script>
  <script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
  <title>脳CAN First-sample１</title>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.3/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet@1.0.3/dist/leaflet.js"></script> 

  </head>

<body id="index">
    <header id="header">
      <nav>
          <ul id="head-li">
            <li><img id="logo" src="img/logo.png"></li>
            <li>HOME</li>
            <li>ABOUT</li>
            <li>PRODUCT</li>
            <li>CONTACT</li>
          </ul>
        </nav>
    </header>

        <div id="col">
          <div id="map"></div>
            <div id="search">
              <div id="act">Activity</div>
              <!-- ここに検索窓 -->
              <form accept-charset="UTF-8" action="" class="flow" id="" method="get" novalidate="">
                <li class="search-li">タイトル</li>
                <input id="act-title" class="input" type="text" value="" size="37" maxlength="20">
                <li class="search-li">シチュエーション</li>
                <input id="situation" class="input" type="text" value="" size="37" maxlength="20">
                <li class="search-li">アクティビティ</li>
                <select id="activity" name="activity_type">
                  <option value="All">すべて</option>
                  <option value="Ride" selected="selected">ライド</option>
                  <option value="Run">ランニング</option>
                  <option value="Swim">水泳</option>
                  <option value="Hike">ハイキング</option>
                  <option value="Walk">ウォーキング</option>
                  <option value="AlpineSki">アルペンスキー</option>
                  <option value="BackcountrySki">バックカントリースキー</option>
                  <option value="Canoeing">カヌーイング</option>
                  <option value="Crossfit">クロスフィット</option>
                  <option value="EBikeRide">電動自転車ライド</option>
                  <option value="Elliptical">エリプティカル</option>
                  <option value="Handcycle">ハンドサイクル</option>
                  <option value="IceSkate">アイススケート</option>
                  <option value="InlineSkate">インラインスケート</option>
                  <option value="Kayaking">カヤッキング</option>
                  <option value="Kitesurf">カイトサーフィン</option>
                  <option value="NordicSki">ノルディックスキー</option>
                  <option value="RockClimbing">ロッククライミング</option>
                  <option value="RollerSki">ローラースキー</option>
                  <option value="Rowing">ローイング</option>
                  <option value="Snowboard">スノーボード</option>
                  <option value="Snowshoe">スノーシュー</option>
                  <option value="StairStepper">ステアーステッパー</option>
                  <option value="StandUpPaddling">スタンドアップパドリング</option>
                  <option value="Surfing">サーフィン</option>
                  <option value="VirtualRide">バーチャル ライド</option>
                  <option value="WeightTraining">ウェイトトレーニング</option>
                  <option value="Wheelchair">車いす</option>
                  <option value="Windsurf">ウインドサーフィン</option>
                  <option value="Workout">ワークアウト</option>
                  <option value="Yoga">ヨガ</option>
                </select>

                <li class="search-li">場所(緯度・経度)</li>
                <input id="act-lat" class="seach-number" type="number">
                <input id="act-lng" class="seach-number" type="number">

                <input id="act-btn" class="btn" type="button" value="Search" form="">

              </form>
            </div>

            <div id="memory">
              <div id="mem-title">Memories</div>
              
              <div id="mem-list">
              </div>
              
              <input id="mem-btn" class="btn" type="button" value="Remember" form="">
              
            </div>
        </div>


<!-- Javascript ーーーーーーーーーーーーーーーーーーーーーーーーーー-->
<script>
    $("#index").fadeIn(2000);


  // マップーーーーーーーーーーーーーーーーーーー
    var map;
    var myLatLng = {lat: 35.667276, lng: 139.713750};
    var marker = [];
    var flightPathCoordinates = new Array();
    var infoWindow = [];

      function initMap() {
        // マップをnew
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 12,
          mapTypeId: 'satellite',
          center: myLatLng
        });
        map.setTilt(45);

        console.log("coordinates",coordinates);
      
          //二重配列をループでpolylineする-----------------------------------------------
          // A階層:レコードごとの処理
          for (let index = 0; index < coordinates.length; index++) {
                var val = coordinates[index]; // 値をコピー
                console.log("index:",index); // 出力：キー名
                console.log("val:",val); // 出力：キー名
              // B階層:１レコード取得した位置情報配列を更にループさせて配列に詰め込む
              // 配列一つ目はタイトルなので、タイトルのみ取得する
              var title = val[0].title;
                // 配列二つ目から実施するため、indexは1が初期値
                for (let index = 1; index < val.length; index++) {
                  const inval = val[index];
                  flightPathCoordinates.push(inval);
                  // console.log("flightPathCoordinates:",flightPathCoordinates);
                }

              // B:１レコード読むたびにポリライン描画---------------------//
                var flightPath = new google.maps.Polyline({
                        path: flightPathCoordinates,
                        geodesic: true,
                        strokeColor: '#0000FF',
                        strokeOpacity: 1.0,
                        strokeWeight: 2
                });

                console.log("flightPath:",flightPath);
                flightPath.setMap(map);
            
              // マーカ、情報ウィンドウを追加
              // B:ポリラインにマーカを追加---//
              marker[index] = new google.maps.Marker(
                {
                position:flightPathCoordinates[flightPathCoordinates.length-1],  //マーカ位置　最後の座標
                map: map,
                draggable: false,  //アイコンの移動の有効無効
                icon: 'img/icon.png',//アイコン指定
                animation: google.maps.Animation.DROP
                });

              // B:ポリラインに情報ウィンドウを追加、open//
              infoWindow[index] = new google.maps.InfoWindow({ // 吹き出しの追加
                content: `<div class="tit">${title}</div><a href="video.html" class=link-video>動画へ</a>` // 吹き出しに表示する内容
              });
              infoWindow[index].open(map, marker[index]); // 情報ウィンドウの表示

              // B:クリック時に跳ねる
              marker[index].addListener('click', toggleBounce);         
              function toggleBounce(e) {
                if (marker[index].getAnimation(e) !== null) {
                  this.setAnimation(null);
                } else {
                  this.setAnimation(google.maps.Animation.BOUNCE);
                }
                infoWindow[index].open(map, marker[index]); // 情報ウィンドウの表示
              };
              // 配列をクリア-------------------//
                flightPathCoordinates.length = 0;
                console.log("clear:",flightPathCoordinates)
      };// end:A階層:レコードごとの処理--------------------------//


      // Searchボタン押下-------------------------//
        // Searchボタンをリスナーget、フォーム値を取得、 文字列を宣言→検索結果を入れる変数
        $("#act-btn").on('click',function name(params) {
          var searchWord = $('#act-title').val();
          console.log("searchWord:",searchWord);

        // タイトルをループで検索して参照
          //検索条件が部分一致であれば何もしない
          // 一致しなかったらクローズする
          for (let index = 0; index < coordinates.length; index++) {
            const search = coordinates[index][0].title;
            console.log('search:',search);
            console.log("coordinates:index:",coordinates[index][0].title)
            if ( search.indexOf(searchWord) != -1) {
              
            } else {
              infoWindow[index].close();
            }
          }
        });

        // 動画リンクを押下した時に遷移する時、値を保存して引き渡す。
        $(document).on("click",".link-video",(e) => {
          let tit = $(e).text();
          console.log(this);
          console.log("tit:",tit);
          sessionStorage.setItem('title',tit)
        });


    };// end:initmap-----------------------------------------------//


</script>
</body>

</html>
