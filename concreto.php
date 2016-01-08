<?
	session_start();
	echo("<head>");
	echo("<script src='java.js'></script>");
	echo("<meta charset='UTF-8'>");
    echo("<title>CRUD</title>");    
    echo("<link rel='stylesheet' href='style.css'>");
	echo("<body>");
	$connessione = mysql_connect("","root","");
	$database=mysql_select_db("my_cortinovis",$connessione);
	$sql="select * from utenti";
	$tutto_il_recordset=mysql_query($sql,$connessione);
	if(isset($_POST['Submit'])){
		$_SESSION['trovato']=false;
		$_SESSION['admin']=false;
		$_SESSION['check']=$_POST['nomeutente'].";".$_POST['psw'];
		$_SESSION['usr']=$_POST['nomeutente'];
		while($_SESSION['trovato']==false && $il_record=MySQL_fetch_array($tutto_il_recordset)){
		if($_SESSION['check']==$il_record["username"].";".$il_record["password"]){
			$_SESSION['trovato']=true;
			if($il_record["admin"]==1)
				$_SESSION['admin']=true;
		}
	}
	}
	/*if(isset($_SESSION['check']))
	{
		header("Location:  ./login.html");
	}*/
	//echo("Ciao, ".$_SESSION["nomeutente"]." sei autorizzato<br/><br/>");
	/*while(!feof($file) && $trovato==false){
		$linea=trim(fgets($file));
		$pezzi=explode(";",$linea);
		if($_SESSION['check']==$pezzi[0].";".$pezzi[1])
		{
			$trovato=true;		
			if($pezzi[2]=='a')
				$admin=true;
		}
	}*/
	if($_SESSION['check']==NULL){
		echo("<h1 class='stile'>Chi cazz'è</h1><br/>");
		echo("<meta http-equiv='refresh' content='2; url=./login.html'>");
		die();
	}
	else{
		if($_SESSION['trovato']==false){
			echo("<h1 class='stile'>Username o password errati</h1><br/>");
			$_SESSION['check']=NULL;
			$_SESSION['trovato']=false;
			$_SESSION['admin']=false;
			echo("<br>");			
			echo("<br>");
			echo("<meta http-equiv='refresh' content='2; url=./login.html'>");
			die();
		}
	}
	if(!isset($_POST["stato"]))
		$stato=0;
	else
		$stato=$_POST["stato"];
	//echo("stato vale ".$stato."<br/>");
	echo("<div class='stile'>");
	echo("<form action='concreto.php' method='post' id='forma'>");
	echo("<input type='hidden' name='stato' id='stato'>");
	switch($stato)
	{
		case 0:	echo("<h1 style='text-align:center; color:white;'>MENU</h1><br/>");
				echo("<button type='submit' class='btn btn-primary btn-block btn-large' onclick='cambia_stato(1);'>Inserimento utente.</button>");
				echo("<button type='submit' class='btn btn-primary btn-block btn-large' onclick='cambia_stato(3);'>Elenco utenti.</button>");
				echo("<button type='submit' class='btn btn-primary btn-block btn-large' onclick='cambia_stato(5);'>Modifica utenti.</button>");
				echo("<button type='submit' class='btn btn-primary btn-block btn-large' onclick='cambia_stato(10);'>Elimina utenti.</button>");
				if($_SESSION['admin']==true)
			   		echo("<button type='submit' class='btn btn-primary btn-block btn-large' onclick='cambia_stato(12);'>Gestione amministratori.</button>");
			   	echo("<button type='submit' class='btn btn-primary btn-block btn-large' onclick='cambia_stato(15);'>Visualizza sorgenti</button>");
			   echo("</div>");
			   break;
		case 1:
				echo("<h1 style='text-align:center; color:white;'> Inserimento nuovo utente</h1><br/>");
			   echo("<input type='text' onload='focus();' name='nomeutente' placeholder='Username' required='required'/>");
        		echo("<input type='password' name='psw' placeholder='Password' required='required'/>");
			   echo('<button type="submit" class="btn btn-primary btn-block btn-large" onclick="cambia_stato2(6);">Aggiungi.</button>');
			   echo("</div>");
			   break;
		case 2:echo("<h1 style=\"text-align:center\">Utente inserito</h1><br/>");
			   break;	
		case 3:
				echo("<h1>Elenco utenti</h1><br/>");
				echo("<table class='bordered' align=center>");
				while($il_record=MySQL_fetch_array($tutto_il_recordset)){
					echo("<tr><td style='color: white'>");
					echo ($il_record["username"]);
					echo("</td></tr>");
				}
				echo('</table>');
				echo("</div>");
			   break;
		case 5: echo("<h1 style='text-align:center; color:white;'>Modifica utenti</h1><br/>");
				if($_SESSION['admin']==true)
					echo('<input type="text" name="utentemodifica" placeholder="Nome utente" required="required"/>');
				else
					echo('<input type="hidden" value="'.$_SESSION['usr'].'" name="utentemodifica" placeholder="Nome utente" required="required"/>');
				echo('<input type="password" name="pswvecchia" placeholder="Vecchia password" required="required"/>');
				echo('<input type="password" name="pswnuova" placeholder="Nuova password" required="required"/>');
				echo('<input type="password" name="pswconferma" placeholder="Conferma nuova password" required="required"/>');
				echo('<button type="submit" class="btn btn-primary btn-block btn-large" onclick="cambia_stato2(8);">Modifica.</button>');	
				echo('</div>');
				break;
		case 6: $user=$_POST['nomeutente'];
				$psw=$_POST['psw'];
				while($il_record=MySQL_fetch_array($tutto_il_recordset)){
					if($user==$il_record["username"])
					{
						echo("<h1 style='text-align:center; color:white;'>Nome utente già esistente</h1>");
						echo("<script>cambia_stato(7)</script>");
						die();
					}
				}
				$sql="insert into utenti (username,password,admin) values ('".$user."','".$psw."','0')";
				if (!mysql_query($sql))
					die("<br/>Errore: ".mysql_error());
				else
					echo("<h1 style='text-align:center; color:white;'>Utente inserito correttamente</h1>");
				echo("<script>cambia_stato(7)</script>");
				echo('</div>');
			break;
		case 7: sleep(2);
				echo("<script>cambia_stato(0)</script>");
				echo('</div>');
				break;
		case 8: $trovatomodifica=false;
				while($trovatomodifica==false && $il_record=MySQL_fetch_array($tutto_il_recordset)){
					if($il_record['username']==$_POST['utentemodifica']){
						$trovatomodifica=true;
						if($il_record['password']==$_POST['pswvecchia']){
							if($_POST['pswnuova']==$_POST['pswconferma']){
								$sql="update utenti set password='".$_POST['pswnuova']."' where username='".$_POST['utentemodifica']."'";
								if (!mysql_query($sql))
									die("<br/>Errore: ".mysql_error());
								else
									echo("<h1 style='text-align:center; color:white;'>Utente modificato correttamente</h1>");
							}
							else
								echo("<h1 style='text-align:center; color:white;'>Le due password non coincidono</h1>");
						}			
						else
							echo("<h1 style='text-align:center; color:white;'>Password sbagliata</h1>");
					}

				}
				if($trovatomodifica==false){
					echo("<h1 style='text-align:center; color:white;'>Nome utente non trovato</h1>");
					echo("<script>cambia_stato(7)</script>");
				}
				echo("<script>cambia_stato(7)</script>");
				echo('</div>');
				break;
		case 9:$_SESSION['check']=NULL;
				$_SESSION['trovato']=false;
				$_SESSION['admin']=false;
				echo("<meta http-equiv='refresh' content='0; url=./login.html'>");
				die();
				break;
		case 10:echo("<h1 style='text-align:center; color:white;'>Cancellazione utente</h1>");
				if($_SESSION['admin']==true)
					echo('<input type="text" name="utenteelimina" placeholder="Nome utente" required="required"/>');
				else
					echo('<input type="hidden" value="'.$_SESSION['usr'].'" name="utenteelimina" placeholder="Nome utente" required="required"/>');
				echo('<input type="password" name="pswelimina" placeholder="Password" required="required"/>');
				echo('<button type="submit" class="btn btn-primary btn-block btn-large" onclick="cambia_stato2(11);">Elimina.</button>');	
				echo('</div>');
				break;
		case 11:$trovatoelimina=false;
				if($_SESSION['admin']==true){
					while($trovatoelimina==false && $il_record=MySQL_fetch_array($tutto_il_recordset)){
						if($il_record['username']==$_POST['utenteelimina']){
							$trovatoelimina=true;
							if($il_record['password']==$_POST['pswelimina']){
									$sql="delete from utenti where username='".$_POST['utenteelimina']."'";
									if (!mysql_query($sql))
										die("<br/>Errore: ".mysql_error());
									else
										echo("<h1 style='text-align:center; color:white;'>Utente eliminato correttamente</h1>");
							}			
							else
								echo("<h1 style='text-align:center; color:white;'>Password sbagliata</h1>");
						}
					}
				}
				else{
					$trovatoelimina=true;
					if($_SESSION['check']==$_POST['utenteelimina'].";".$_POST['pswelimina']){
						$sql="delete from utenti where username='".$_POST['utenteelimina']."'";
						if (!mysql_query($sql))
							die("<br/>Errore: ".mysql_error());
						else
							echo("<h1 style='text-align:center; color:white;'>Utente eliminato correttamente</h1>");
						$_SESSION['check']=NULL;
						$_SESSION['trovato']=false;
						$_SESSION['admin']=false;
						echo("<meta http-equiv='refresh' content='2; url=./login.html'>");
						die();
					}
					else
						echo("<h1 style='text-align:center; color:white;'>Password sbagliata</h1>");
				}
				if($trovatoelimina==false){
					echo("<h1 style='text-align:center; color:white;'>Nome utente non trovato</h1>");
					echo("<script>cambia_stato(7)</script>");
				}
				else
				echo("<script>cambia_stato(7)</script>");
				echo('</div>');
				break;
		case 12:if($_SESSION['admin']==false){
					echo("<h1 style='text-align:center; color:white;'>Non sei abilitato</h1>");
					echo("<script>cambia_stato(7)</script>");
					die();
				}
				echo("<h1 style='text-align:center; color:white;'>Gestione amministratori</h1><br/>");
				echo('<input type="password" name="pswadmin" placeholder="Password" required="required"/>');
				echo("<button type='submit' class='btn btn-primary btn-block btn-large' onclick='cambia_stato(13);'>Vai alla gestione amministratori.</button>");
				echo('</div>');
				break;
		case 13:if($_SESSION['check']==$_SESSION['usr'].";".$_POST['pswadmin']){
					echo('</div>');
					echo("<h3>Verde: amministratore, premere per togliere i permessi | Rosso: non amministratore, premere per dare i permessi</h1><br/>");
					echo('<div class="stile">');
					while($il_record=MySQL_fetch_array($tutto_il_recordset)){
						if($il_record['admin']==0){
							echo("<button type='submit' style='border-color:red;background-color:red;' class='btn-block btn-large' onclick='gestadm(".$il_record['id_utente'].");'>".$il_record['username']."</button>");
						}
						else{
							echo("<button type='submit'style='border-color:green;background-color:green;' class='btn-block btn-large' onclick='gestadm(".$il_record['id_utente'].");'>".$il_record['username']."</button>");
						}
					}
					echo('<input id="campoutente" type="hidden" name="usradmin" placeholder="Nome utente"/>');
				}
				else{
					echo("<h1 style='text-align:center; color:white;'>Password errata</h1><br/>");
					echo("<script>cambia_stato(7)</script>");
					die();
				}
				echo('</div>');
				break;
		case 14:while($il_record=MySQL_fetch_array($tutto_il_recordset)){
					if($il_record['id_utente']==$_POST['usradmin'])
						if($il_record['username']=='cortinovis'){
							echo("<h1 style='text-align:center; color:white;'>Non puoi togliere l'amministratore supremo</h1><br/>");
							echo("<script>cambia_stato(7)</script>");
							die();
						}
						else{
							if($il_record['username']==$_SESSION['usr'])
								$_SESSION['admin']=0;
							$controllaadmin=$il_record['admin'];
						}
				}
				if($controllaadmin==0)
					$sql="update utenti set admin=1 where id_utente=".$_POST['usradmin'];
				else
					$sql="update utenti set admin=0 where id_utente=".$_POST['usradmin'];
				if (!mysql_query($sql))
					die("<br/>Errore: ".mysql_error());
				else
					echo("<h1 style='text-align:center; color:white;'>Gestione amministratori aggiornata</h1>");
				echo("<script>cambia_stato(7)</script>");
				echo('</div>');
				break;
		case 15:echo("<button type='submit' style='border-color:red;background-color:red;' class='btn-block btn-large' onclick='cambia_stato(16);'>LOGIN (HTML)</button>");
				echo("<button type='submit' style='border-color:red;background-color:red;' class='btn-block btn-large' onclick='cambia_stato(17);'>PHP</button>");
				echo("<button type='submit' style='border-color:red;background-color:red;' class='btn-block btn-large' onclick='cambia_stato(18);'>CSS</button>");
				echo("<button type='submit' style='border-color:red;background-color:red;' class='btn-block btn-large' onclick='cambia_stato(19);'>JAVASCRIPT</button>");
				echo('</div>');
				break;
		case 16:echo("<meta http-equiv='refresh' content='0; url=./login.txt'>");
				break;
		case 16:echo("<meta http-equiv='refresh' content='0; url=./php.txt'>");
				break;	
		case 16:echo("<meta http-equiv='refresh' content='0; url=./css.txt'>");
				break;
		case 16:echo("<meta http-equiv='refresh' content='0; url=./java.txt'>");
				break;	
	}
			echo('</form>');
			echo('<button type="submit" style="text-align:right;" class="btn btn-primary btn-large" onclick="cambia_stato(0);">Home</button>');
			echo('<button type="submit" style="text-align:right;" class="btn btn-primary btn-large" onclick="cambia_stato(9);">Logout</button>');
	echo("</form>");
	mysql_close($connessione);
?>
