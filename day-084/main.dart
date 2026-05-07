import 'package:english_words/english_words.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

void main() {
  // Indique à Fluter d'éxécuter l'application définit dans MyApp.
  runApp(MyApp());
}

// Ce bloc de code définit la configuration générale (thème, nom, état) de mon projet.
/*
StatelessWidget dans Flutter est un composant d'interface utilisateur immuable.
MyApp en héritant de StatelessWidget, devient un Widget que FLutter est capable de comprendre,
dessiner et  de placer dans l'interface.
MyApp sert de racine (le squelette global) à mon application.
Elle définit le titre, le thème (Material3) et la couleur de base.

Une fois que ces réglages sont chargés au démarrage,
ils ne changent généralement plus.
MyApp n'a pas besoin de se "redessiner" sans cesse;
elle délègue la gestion des changements de données à ChangeNotifierProvider.
 */
class MyApp extends StatelessWidget {
  /*
  Le constructeur.
  key permet à Flutter d'identifier mon widget de manière unique dans l'arbre des composants.
   */
  const MyApp({super.key});
  @override
  /*
  La méthode build est obligatoire,
  elle  décrit à quoi va ressembler mon interfare.

  context : contient les infos sur l'emplacement du widget dans l'application.
   */
  Widget build(BuildContext context) {
    /*
    ChangeNotifierProvider un Widget spécial issu du paquet provider,
    permet de partager des données entre plusieurs widgets et
    de mettre à jour automatiquement l'écran quand ces données changent.
    Il permet de "diffuser" des données à toute ton application.
    Si les données changent, les widgets concernés se reconstruiront automatiquement.
     */
    return ChangeNotifierProvider(
      /*
      create :
      c'est l'endroit précis où je crée l'instance (l'objet)
      qui va contenir les données de mon application.

      create: contexte est la carte géographique de mon app
      qui permet aux widgets de savoir ou aller récupérer
      tous les données ( fournit par un Provider) dont mon app a besoin.
      (chaque Widget a son propre context).
       */
      create: (context) => MyAppState(),
      child: MaterialApp(
        title: 'Namer App',
        theme: ThemeData(
          useMaterial3: true,
          colorScheme: ColorScheme.fromSeed(seedColor: Colors.blue),
        ),
        home: MyHomePage(),
      ),
    );
    // Pour gérer plusieurs classes de données,
    // le paquet provider propose un widget spécifique : MultiProvider :
    // Widget build(BuildContext context) {
    //   return MultiProvider( // On utilise MultiProvider à la place de ChangeNotifierProvider
    //     providers: [
    //       // On liste ici tous les fournisseurs de données.
    //       ChangeNotifierProvider(create: (context) => MyAppState()),
    //       ChangeNotifierProvider(create: (context) => MyAppState2()),
    //     ],
    //     child: MaterialApp(
    //       title: 'Namer App',
    //       theme: ThemeData(
    //         useMaterial3: true,
    //         colorScheme: ColorScheme.fromSeed(seedColor: Colors.deepOrange),
    //       ),
    //       home: MyHomePage(),
    //     ),
    //   );
    // }

  }
}

// classe pour contenir les mots.
class MyAppState extends ChangeNotifier {
  var current = WordPair.random();
  void getNext() {
    current = WordPair.random();
    notifyListeners();
  }
  var favorites = <WordPair>[];

  void toggleFavorite() {
    if (favorites.contains(current)) {
      favorites.remove(current);
    } else {
      favorites.add(current);
    }
    notifyListeners();
  }
}

class MyAppState2 extends ChangeNotifier {
  var current = WordPair.random();
}

class MyHomePage extends StatelessWidget {
  @override
  /*
  Je peux voir BuildContext context comme un GPS ou un guide local propre à chaque Widget.
  BuildContext context : connaît l'adresse du Widget.
    1. Chaque widget a son propre context.
       Quand un widget est créé, Flutter lui donne un context qui contient
       sa position exacte dans l'arbre des widgets (la structure de ton code).
    2. context ne regarde jamais vers le bas (ses enfants),
       il regarde toujours vers le haut (ses parents/ancêtres).
       context.watch<MyAppState>() =>
          remonte l'arbre généalogique de l'application
          jusqu'à ce qu'à trouver un fournisseur (Provider)
          qui propose des données de type MyAppState.
  Si j'ai plusieurs quartiers dans mon application (plusieurs pages ou sections),
  le context sait exactement quel Provider est disponible à cet endroit précis :
      1. Si un Provider est au sommet (dans MyApp),
         tous les widgets peuvent y accéder via leur context.
      2. Si un Provider est placé seulement au-dessus d'une petite branche
         (une page spécifique), seuls les widgets de cette branche pourront le trouver.
  context.watch<T>() :
      Je surveille ce type de données.
      Si elles changent, préviens-moi pour que je me redessine.
  context.read<T>()  :
      Donne-moi la valeur actuelle une seule fois
      (souvent utilisé dans un bouton), mais ne me surveille pas.
   */
  Widget build(BuildContext context) {
    var appState = context.watch<MyAppState>();
    var pair = appState.current;
    IconData icon;
    if (appState.favorites.contains(pair)) {
      icon = Icons.favorite;
    } else {
      icon = Icons.favorite_border;
    }
    /*
    Scaffold est la structure de base d'une page,
    Il gère le fond blanc, la barre de nav ou les boutons flottants.
    C'est le "squellete visuel" de mon écran.
    Il découpe mon écran en zones spécifiques et gère leur placement automatiquement.
    Les plus courant :
      1.AppBar (Le toit) : La barre tout en haut avec le titre.
      2.Body (Le salon) : L'espace principal au centre.
      3.FloatingActionButton :
        Le bouton rond flottant souvent en bas à droite (comme pour écrire un nouveau mail dans Gmail).
      4.Drawer : Le menu latéral qui glisse quand on clique sur un bouton "hamburger".
      5.BottomNavigationBar : La barre de navigation en bas de l'écran.
     */
    return Scaffold(
      /*
      Le body est le contenu principal comme en Html.
      Le body ne peut accepter qu'un seul widget (un seul enfant).
      Que faire si je veux 3 objets là ou il n'ya de la place que pour un seul ?
      Column (L'Organisateur) un widget spécial qui agit comme une boîte transparente.
      Boy coontient ue seule Colonne, Colonne contient plusieurs enfants.
      Colonne prend tout ce que je lui done et les empile verticalement ( de haut en bas).
      */
      //
      body: Center(
        child: Column( // 1. Le body contient LA Colonne
          mainAxisAlignment: MainAxisAlignment.center, // Centre l'enfant dans la Column, le long de l'axe principal (verticalement).
          children: [ // 2. La liste des enfants commence ici (crochets [])
            // Text('A random idea:00'), //    - Élément 1 (Haut)
            BigCard(pair: pair), //    - Élément 2 (Milieu)
            SizedBox(height: 10),//  créer des séparations visuelles.
            // Btn Next en bas du mot random :
            // -----------------------------------
            Row(
              mainAxisSize: MainAxisSize.min,  // Prendre le minimum de place possible horizontalement.
              children: [
                ElevatedButton.icon( //    - Élément 3 (Bas)
                  onPressed: (){ // Lorsque je clique dessus, il exécute cette action :
                    // Affiche le texte dans la console.
                    print('button Like pressed');
                    appState.toggleFavorite();
                  },
                  icon: Icon(icon),
                  label: Text('Like'),
                ),
                SizedBox(width: 10), //  créer des séparations visuelles.
                ElevatedButton( //    - Élément 3 (Bas)
                    onPressed: (){ // Lorsque je clique dessus, il exécute cette action :
                      // Affiche le texte dans la console.
                      print('button Next pressed');
                      appState.getNext();
                    },
                    child: Text('Next'), // L'étiquette du bouton.
                )
              ],
            ),
            // -----------------------------------

          ],

        ),
      ),
    );
  }
}

class BigCard extends StatelessWidget {
  const BigCard({
    super.key,
    required this.pair,
  });

  final WordPair pair;
  @override

  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    // theme.textTheme, permet d'accéder au thème des polices.
    final style = theme.textTheme.displayMedium!.copyWith(
      color: theme.colorScheme.onPrimary,
    );
    return Card(
      color: theme.colorScheme.primary,
      child: Padding(
      /*
      Wrap with" (Envelopper) :
      Puisque l'espace est une brique à part entière,
      pour ajouter de l'espace autour de mon texte,
      je dois prendre ma brique Text et l'enfermer à l'intérieur
      d'une nouvelle brique appelée Padding.
      C'est comme mettre un objet dans une boîte remplie de papier bulle !
       */
        padding: const EdgeInsets.all(20), //  marge intérieure
        // child: Text(pair.asLowerCase),
        // child: Text(pair.asLowerCase, style: style),
        // les lecteurs d'écrans prononcent correctement chaque paire de mots générée sans que l'UI ait changé.
        child: Text(
          pair.asLowerCase,
          style: style,
          semanticsLabel: "${pair.first} ${pair.second}",
        ),
      ),
    );
  }
}
