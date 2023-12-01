<!-- @import jquery & sweet alert  -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<?php 
// @Connection database
// $con = new mysqli('','root','','');
     $connection = new mysqli('localhost:3307', '', '','etec_project');
     if (!function_exists('get_logo')) {
          function get_logo($status){
               global $connection;
               $sql = "SELECT `thumbnail` FROM `logo` WHERE `status`='$status' ORDER BY id LIMIT 1";
               $result = $connection->query($sql);
               $row = mysqli_fetch_assoc( $result );
              echo $row['thumbnail'];
         }
    
         function getTrendingNews(){
              global $connection;
              $sql = "SELECT * FROM `news` ORDER BY id LIMIT 3";
              $result = $connection->query($sql);
              while($row = mysqli_fetch_assoc( $result )){
                   echo '
                       <i class="fas fa-angle-double-right"></i>
                       <a href="news-detail.php?id='.$row['id'].'">'.$row['title'].'</a> &ensp;
                   ';
              }
         }
    
         function getNewsDetail($post_id){
              global $connection;
              $sql = "SELECT * FROM `news` WHERE id = $post_id LIMIT 3";
              $result = $connection->query($sql);
              $row = mysqli_fetch_assoc( $result );
              $date = $row["create_at"];
              $date = date('d/m/y');
              echo '
                  <div class="thumbnail">
                     <img src="../admin/assets/image/'.$row['banner'].'">
                  </div>
                  <div class="detail">
                      <h3 class="title">'.$row['title'].'</h3>
                      <div class="date">'.$date.'</div>
                      <div class="description">'.$row['description'].'</div>
                   ';
    
         }
         function getNewsType($id){
              global $connection;
              $sql = "SELECT * FROM `news` WHERE id=$id";
              $result = $connection->query($sql);
              $row = mysqli_fetch_assoc( $result );
              return $row['type'];
         }
         function getRateNews($news_id){
              global $connection;
              $news_content = getNewsType($news_id);
              $sql = "SELECT * FROM `news` WHERE `type`='$news_content' AND id NOT IN ($news_id) ORDER BY id LIMIT 3";
              $result = $connection->query($sql);
              while($row = mysqli_fetch_assoc( $result )){
                   $date = $row["create_at"];
                   $date = date('d/m/y');
                   echo '
                   <figure>
                   <a href="./news-detail.php?id='.$row['id'].'">
                       <div class="thumbnail">
                           <img src="../admin/assets/image/'.$row['thumbnail'].'"width="350px" hieght="200px" style="object-fit: cover" alt="">
                       </div>
                       <div class="detail">
                           <h3 class="title">'.$row['title'].'</h3>
                           <div class="date">'.$date.'</div>
                           <div class="description">'.$row['description'].'</div>
                       </div>
                   </a>
               </figure>
                        ';
              }
         }

         function getMinNews($type){
          global $connection;
           if($type == 'Trending'){
               $sql = "SELECT * FROM `news` ORDER BY view DESC LIMIT 1";
               $result = $connection->query($sql);
               $row = mysqli_fetch_assoc( $result );
               echo '
               <div class="col-12">
                    <figure>
                      <a href="news-detail.php?id='.$row['id'].'">
                          <div class="thumbnail">
                              <img src="../admin/assets/image/'.$row['banner'].'" width="730px" height="415px" style="object-fit:cover" alt="">
                              <div class="title">'.$row['title'].'</div>
                          </div>
                      </a>
                    </figure>
               </div>
                    ';
           }else{
               $sql = "SELECT * FROM `news` WHERE id != (SELECT id FROM `news` ORDER BY view DESC LIMIT 1) ORDER BY id DESC LIMIT 2";
               $result = $connection->query($sql);
               while($row = mysqli_fetch_assoc( $result )){
                    echo '
                    <div class="col-12">
                        <figure>
                            <a href="news-detail.php?id='.$row['id'].'">
                                <div class="thumbnail">
                                    <img src="../admin/assets/image/'.$row['thumbnail'].'" width="350px" height="200px" style="object-fit: cover" alt="">
                                <div class="title">'.$row['title'].'</div>
                                </div>
                            </a>
                        </figure>
                    </div>
                         ';
               }
           }
         }
         function getSportNews($type){
          global $connection;
          $sql="SELECT * FROM `news` WHERE category='$type' ORDER BY id DESC";
          $result = $connection->query($sql);
          while($row = mysqli_fetch_assoc( $result )){
               echo '
               <div class="col-4">
                    <figure>
                        <a href="news-detail.php?id='.$row['id'].'">
                            <div class="thumbnail">
                                <img src="../admin/assets/image/'.$row['thumbnail'].'" width="350px" height="200px" style="object-fit: cover" alt="">
                            <div class="title">'.$row['title'].'</div>
                            </div>
                        </a>
                    </figure>
                </div>
                    ';
          }
         }

         function getSocialNews($social_data){
          global $connection;
          $sql="SELECT * FROM `news` WHERE category='$social_data' ORDER BY id DESC";
          $result = $connection->query($sql);
          while($row = mysqli_fetch_assoc( $result )){
               echo '
               <div class="col-4">
                    <figure>
                        <a href="news-detail.php?id='.$row['id'].'">
                            <div class="thumbnail">
                                <img src="../admin/assets/image/'.$row['thumbnail'].'" width="350px" height="200px" style="object-fit: cover" alt="">
                            <div class="title">'.$row['title'].'</div>
                            </div>
                        </a>
                    </figure>
                </div>
                    ';
          }
         }

         function getEntertainmentNews($entertainment_data){
          global $connection;
          $sql="SELECT * FROM `news` WHERE category='$entertainment_data' ORDER BY id DESC";
          $result = $connection->query($sql);
          while($row = mysqli_fetch_assoc( $result )){
               echo '
               <div class="col-4">
                    <figure>
                        <a href="news-detail.php?id='.$row['id'].'">
                            <div class="thumbnail">
                                <img src="../admin/assets/image/'.$row['thumbnail'].'" width="350px" height="200px" style="object-fit: cover" alt="">
                            <div class="title">'.$row['title'].'</div>
                            </div>
                        </a>
                    </figure>
                </div>
                    ';
          }
         }
          
         function list_news($category, $news_type, $page, $limit){
          global $connection;
          $start = ($page -1) * $limit;
          $sql= "SELECT * FROM `news` WHERE (`category`='$category' AND `type`='$news_type') ORDER BY id LIMIT $start,$limit ";
          $result = $connection->query($sql);
          while($row = mysqli_fetch_assoc($result)){
               $date = $row['create_at'];
               $date = date('d/m/y');
               echo '
               <div class="col-4">
                   <figure>
                       <a href="news-detail.php?id='.$row['id'].'">
                           <div class="thumbnail">
                               <img src="../admin/assets/image/'.$row['thumbnail'].'" width="350px" height="200px" style="object-fit: cover" alt="">
                           </div>
                           <div class="detail">
                               <h3 class="title">'.$row['title'].'</h3>
                               <div class="date">'.$date.'</div>
                               <div class="description">'.$row['description'].'</div>
                           </div>
                       </a>
                   </figure>
               </div>
               ';
          }
         }
         function page($category, $news_type, $page, $limit){
          global $connection;
          $sql= "SELECT COUNT(id) as total_post FROM news WHERE `category`='$category' AND `type`='$news_type'";
          $result = $connection->query($sql);
          $row = mysqli_fetch_assoc( $result );
          $pageination = ceil($row["total_post"]/$limit);
         }
        
     }
?>