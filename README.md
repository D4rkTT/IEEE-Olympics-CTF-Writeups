# IEEE Olympics CTF Competition Writeups

### Description
IEEE Olympics CTF Competition organized and managed by IEEE Mansoura Student Branch & EG CERT

CTF Competition Style: jeopardy

Time: 6 hours

### Challenges
[1- Web : Inj3ct th!s - 200 points]()

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
