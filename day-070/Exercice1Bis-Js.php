wq<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<!DOCTYPE html>
<html>
    <head>
        <title>Liste bornée</title>
        <meta charset="utf-8">
    </head>
    <body>
        <table>
            <tr>
                <td>Borne inférieure :</td>
                <td><input id="inf" type="number" value="1"><br></td>
            </tr>
            <tr>
                <td>Borne supérieure :</td>
                <td><input id="sup" type="number" value="20"><br></td>
            </tr>
            <tr>
                <td colspan="2"> <input id="btn" type="button" value="Générer liste" onclick="generateList();"> </td>
            </tr>
        </table>
        <p id="list"></p>
        <script>
            function generateList() {
                // console.log(2);
                // parseInt() analyse une chaîne de caractère fournie en argument et renvoie un entier exprimé dans une base donnée.
                const inf = parseInt(document.getElementById("inf"));
                const sup = parseInt(document.getElementById("sup"));
                console.log(inf);
                let list = document.getElementById("list");
                let list_puce = "<ul>";
                for(let i = inf; i< sup; i++){
                    console.log(i);
                    list_puce += "<li>"+i+"</li>";

                }
                let list_puce += "</ul>";
                document.getElementById("list").innerHTML = list_puce;
            }
        </script>
    </body>
</html>