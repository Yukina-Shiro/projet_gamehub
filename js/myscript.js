
function submit(){
document.getElementById ("form.id").submit();

}
        
// // Fonction pour calculer le prix total
// 		function setPrxTotal() {
// 			objPrxTotal = document.getElementById("idprixtotal");
// 			objPrxReprise = document.getElementById("idprixreprise");
// 			objPrxOption = document.getElementById("idprixoption");
// 			objPrxCouleur = document.getElementById("idprixcouleur");
// 			objPrxModele = document.getElementById("idprixmodele");
			
// 			prxReprise = parseInt(objPrxReprise.value);
// 			prxOption = parseInt(objPrxOption.value);
// 			prxCouleur = parseInt(objPrxCouleur.value);
// 			prxModele = parseInt(objPrxModele.value);
			
// 			objPrxTotal.value = prxModele + prxCouleur + prxOption - prxReprise;
// 		}
		
// 		// fonction pour affecter le prix d'un modele
// 		function setPrixInitial(marque, modele) {
// 		   prxModele = prix[marque][modele];
			  
// 		   // affectation du champ prix du modele
// 		   objPrxModele = document.getElementById("idprixmodele");
// 		   objPrxModele.value = prxModele;
		   
// 		   // affectation du prix total
// 		   setPrxTotal();
// 		}
		
// 		// fonction pour definir un selecteur de maniere dynamique
// 		function setSelector( id, tab ) {
// 			sel = document.getElementById(id);
// 			k = 0;
// 			for ( i in tab) {
// 				sel.options[k] = new Option();
// 				sel.options[k].value = i;
// 				sel.options[k].text = tab[i];
// 				k++;
// 			}
// 		}
		
// 		function setSelectorModele() {
// 			// recuperation de la marque choisie
// 			selMarque = document.getElementById("idmarque");
// 			index = selMarque.selectedIndex;
// 			selMarqueValue = selMarque.options[index].value;
// 			// On affecte les modeles et le prix si la valeur n'est pas vide
// 			if ( selMarque.value.length > 0 ) {
// 			  // affectation des modeles de la marque
// 			  setSelector("idmodele", modeles[selMarqueValue]);
			  
// 			  // recherche du premier modele de la marque
// 			  fstModeleMarque = getFirstValue (modeles[selMarqueValue]);
			  
// 			  // affectation du champ prix du premier modele
// 			  setPrixInitial(selMarqueValue, fstModeleMarque);
// 			}
// 		}
		
// 		function setPrixModele(selModele) {
// 			// recuperation de la marque choisie
// 			selMarque = document.getElementById("idmarque");
// 			index = selMarque.selectedIndex;
// 			selMarqueValue = selMarque.options[index].value;
			
// 			// recuperation du modele choisi
// 			index = selModele.selectedIndex;
// 			selModeleValue = selModele.options[index].value;
			
// 			// affectation du champ prix
// 			setPrixInitial(selMarqueValue, selModeleValue);
// 		}
// function setPrxCouleur() {
//     colButCouleur = document.getElementsByName("couleur");
    
//     objPrxCouleur = document.getElementById("idprixcouleur");
    
//     for ( i = 0; i < colButCouleur.length; i++) {
//         if ( colButCouleur[i].checked )
//            objPrxCouleur.value = colButCouleur[i].value;
//     }
    
//     setPrxTotal();
// }

// function setPrxOptions() {
//     colButOptions = document.getElementsByName("option");
    
//     objPrxOption = document.getElementById("idprixoption");
    
//     prixOptions = 0;
//     for ( i = 0; i < colButOptions.length; i++) {
//         if ( colButOptions[i].checked )
//           prixOptions += parseInt(colButOptions[i].value);
//     }
//     objPrxOption.value = prixOptions;
    
//     setPrxTotal();
// }

// function setPrxReprise() {
//     objPrixReprise = document.getElementById("idprixreprise");
//     prix = parseInt(objPrixReprise.value);
    
//     if ( prix < 1000 ) {
//       prix = 1000;
//       alert("Le prix de la reprise doit etre superieur a 1000 Euro"); 
//     }
      
//     if ( prix > 4500 ) {
//       prix = 4500;
//       alert("Le prix de la reprise doit etre inferieur a 4500 Euro"); 
//     }
      
//     setPrxTotal();
// }
// function resetForm() {
//     // On rend inactif les champ de texte 
//     objPrxModele = document.getElementById("idprixmodele");
//     objPrxModele.disabled = true;
//     objPrxCouleur = document.getElementById("idprixcouleur");
//     objPrxCouleur.disabled = true;
//     objPrxOption = document.getElementById("idprixoption");
//     objPrxOption.disabled = true;
//     objPrxTotal = document.getElementById("idprixtotal");
//     objPrxTotal.disabled = true;			

//     // Marque & Modele
//     setSelector('idmarque', marques);
//     setSelectorModele();
    
//     // Couleurs
//     document.getElementById("clr1").checked = true;
//     setPrxCouleur();
//     // Options
//     colButOptions = document.getElementsByName("option");
//     for ( i = 0; i < colButOptions.length; i++)
//        colButOptions[i].checked = false;
//     setPrxOptions();
//     // Reprise
//     objPrixReprise = document.getElementById("idprixreprise");
//     objPrixReprise.value = 1000;
    
//     setPrxTotal();
// }

// function checkForm() {
//     objNom = document.getElementById("idnom");
//     objPrenom = document.getElementById("idprenom");
//     objMail = document.getElementById("idmail");

//     objNomNote = document.getElementById("idnomnote");
//     objPrenomNote = document.getElementById("idprenomnote");
//     objMailNote = document.getElementById("idmailnote");

//     if ( objNom.value.length == 0 )
//        alert("Le champ nom est obligatoire");

//     if ( objPrenom.value.length == 0 )
//        alert("Le champ prenom est obligatoire");

//     if ( objEmail.value.length == 0 )
//        alert("Le champ email est obligatoire");

//     // Check du mail
    
//     //var patt1=/[^0-9a-zA-Z_-@]/g;
//     var patt1=/[^a-z]/g;
//     var strmail = objEmail.value;
//     /*
//     alert("reg exp mail : " + strmail.match(patt1));
//     if ( strlen(strmail.match(patt1)) > 0 )
//         alert("Le champ email contient des caracteres incorrects");
//     */
// }
