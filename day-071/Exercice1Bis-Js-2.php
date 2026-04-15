<!DOCTYPE html>
<html>
    <head>
        <title>Liste bornée</title>
        <meta charset="utf-8">
    </head>
    <body>
         <script>
            let list,inf,sup,tmp;
            // onreadystatechange est déclenché à chaque fois que l'état de la page est modifié.
            document.onreadystatechange = function () {
                //  document.readyState === 'complete' chargement complet de la page.
                 if (document.readyState === 'complete') {
                    list = document.getElementById("list");
                    // parseInt() => transforme un string en int.
                    inf = parseInt(document.getElementById("inf").value);
                    sup = parseInt(document.getElementById("sup").value);
                }
            }
            function generateList() {
                let list_puce = "<ul>"; 
                for(let i = inf;i<=sup;i++){
                    list_puce += "<li>"+i+"</li>";
                    console.log(i);
                }
                list_puce += "</ul>";
                return list.innerHTML = list_puce;
            }

            function boundaryChanged(field){
                // console.log(field.value);
                // console.log(field.id);
                // Mets à jours les données.
                sup = parseInt(document.getElementById("sup").value);
                inf = parseInt(document.getElementById("inf").value);

                let errInf = document.getElementById("errInf");
                let errSup = document.getElementById("errSup");
                let btn = document.getElementById("btn");
                errInf.style.color="red";
                errSup.style.color="red";

                if(field.id ==="inf" && parseInt(field.value) >= sup){
                    errInf.innerHTML = "doit être strictement inférieur à Sup.";
                    list.innerHTML ="";
                    btn.disabled = true;
                    console.log(field.value);

                }else if(field.id ==="sup" && parseInt(field.value) < inf){
                    errSup.innerHTML = "doit être strictement supérieur à Sup.";
                    list.innerHTML ="";
                    btn.disabled = true;
                    console.log(field.value);
                }else{
                    errInf.innerHTML="";
                    errSup.innerHTML="";
                    btn.disabled =false;
                }
            }
        </script>    
        <table>
            <tr>
                <td>Borne inférieure :</td>
                <!--onChange event handler is triggered whenever the value of an input element changes. -->
                <td><input id="inf" type="number" value="1" onchange="boundaryChanged(this);"><br></td>
                 <td id="errInf"></td>
            </tr>
            <tr>
                <td>Borne supérieure :</td>
                <td><input id="sup" type="number" value="20"  onchange="boundaryChanged(this);"><br></td>
                 <td id="errSup"></td>
            </tr>
            <tr>
                <td colspan="3"> <input id="btn" type="button" value="Générer liste" onclick="generateList();"> </td> 
            </tr>
        </table>
        <p id="list"></p>
    </body>
</html>