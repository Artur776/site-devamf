// FirebaseConnection.php
<?php
class FirebaseConnection
{
  private $firebase_url;
  private $api_key;

  public function __construct()
  {
    $config = include("firebaseConfig.php");
    $this->firebase_url = $config["firebase_url"];
    $this->api_key = $config["api_key"];
  }

  public function registerUser($email, $password)
  {
    $url = "https://identitytoolkit.googleapis.com/v1/accounts:signUp?key=" . $this->api_key;
    $data = json_encode(["email" => $email, "password" => $password, "returnSecureToken" => true]);
    return $this->sendRequest($url, $data);
  }

  public function loginUser($email, $password)
  {
    $url = "https://identitytoolkit.googleapis.com/v1/accounts:signInWithPassword?key=" . $this->api_key;
    $data = json_encode(["email" => $email, "password" => $password, "returnSecureToken" => true]);
    return $this->sendRequest($url, $data);
  }

  public function insertData($path, $data)
  {
    $url = $this->firebase_url . $path . ".json";
    return $this->sendRequest($url, json_encode($data), "POST");
  }

  public function getData($path)
  {
    $url = $this->firebase_url . $path . ".json";
    return $this->sendRequest($url, "", "GET");
  }

  public function updateData($path, $data)
  {
    $url = $this->firebase_url . $path . ".json";
    return $this->sendRequest($url, json_encode($data), "PATCH");
  }

  public function deleteData($path)
  {
    $url = $this->firebase_url . $path . ".json";
    return $this->sendRequest($url, "", "DELETE");
  }

  private function sendRequest($url, $data, $method = "POST")
  {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    if ($method != "GET") {
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
      curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    }
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
  }
}
?>

// index.php
<?php
include("FirebaseConnection.php");
$firebase = new FirebaseConnection();

// Exemplo de uso
//$result = $firebase->registerUser("email@example.com", "123456");
//print_r($result);

//$result = $firebase->loginUser("email@example.com", "123456");
//print_r($result);

//$result = $firebase->insertData("users/user1", ["name" => "João", "age" => 25]);
//print_r($result);

//$result = $firebase->getData("users/user1");
//print_r($result);

//$result = $firebase->updateData("users/user1", ["age" => 26]);
//print_r($result);

//$result = $firebase->deleteData("users/user1");
//print_r($result);
?>