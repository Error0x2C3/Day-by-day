Syntaxe : 
===========================================

------------------------------------------
**base**
```java
int[] array1 = new int[10];
int[] array2 = {1, 2, 3, 4};
````
```java
int[] array = {1, 2, 3, 4};
for (int i = 0; i < array.length; ++i) {
System.out.print(array[i] + " ");
}
System.out.println();

int[] array = {1, 2, 3, 4};
for (int elem : array) {
System.out.print(elem + " ");
}
System.out.println();
```
```java
String msg = i == j ? "equals" : "not equals";
char c = line == 0 ? '+' : line == width - 1 ? '=' : 'o’;
```
------------------------------------------
**gestion des exceptions.**
```java
class CustomException extends Exception {
    CustomException ( String message ){
        super ("( custom exception )" + message );
    }
}
class Program {
    static void work () throws CustomException {
        throw new CustomException (" the error ");
    }
    static void main (){
        try {
            work ();
        }
    }
}
```
```java
/*
Les méthodes qui peuvent déclencher une exception checked doivent le déclarer dans leur signature via
le mot clé throws.
*/
public static void b() throws Exception {
        throw new Exception("b1"); // L'exception est levée ici et transmise à l'appelant
}
try {
} catch (Exception exception) {
System.out.println(exception.getMessage());
}
```
------------------------------------------
**scanner**
```java
System.out.print("Entrez 3 entiers séparés par des espaces (JJ MM YYYY) : ");
System.out.print();
Scanner scan = new Scanner(System.in);
// String a = scan.nextLine(); sinon le programme ne vas pas prendre les mots tapés.
ArrayList<Integer> list = new ArrayList<>();
int i = 0;
while(scan.hasNext()){
    // Demandera infiniement un nombre car hasNext() attend indéfiniement une nouvelle entrée.
    System.out.println( Integer.valueOf(scan.next()) );
    list.add(Integer.valueOf(scan.next()) );
    System.out.println(list.get(i));
    i+=1;
}
// Autre exemple d'usage d'un scanner.
Scanner scanner = new Scanner(TEXT);
scanner.useDelimiter("[\\p{Punct}\\s’]+");
// ou 
Scanner in = new Scanner(file);
in.useDelimiter("[\\p{Punct}\\s’]+");

System.out.print("Entrez 3 entiers séparés par des espaces (JJ MM YYYY) : ");
Scanner scan = new Scanner(System.in);
// On lit exactement 3 entiers
int jour = scan.nextInt();
int mois = scan.nextInt();
int annee = scan.nextInt();    
```
------------------------------------------
**TreeMap, treeSet / HashMap, hashSet**
```java
Map<Date,List<Task> > agenda = new TreeMap<>();
agenda.put(new Date(date.getDay(),date.getMonth(),date.getYear()),taskList);
for(Date elem : agenda.keySet()){
        System.out.println(elem);
}
for (Map.Entry<Date, List<Task>> entry : agenda.entrySet()) {
    Date date = entry.getKey();
    List<Task> tasks = entry.getValue();
    System.out.println(date + " : " + tasks);
}
Set<Person> set = new TreeSet<>(); // Ordre + pas de doublon.
Set<Person> set2 = new HashSet<>(); // Pas triée + pas de doublon.
@Override
public int hashCode(){
    return Objects.hash(this.firstName,this.lastName,this.getAge());
}
// Si deux objets sont identiques (au sens d'equals), alors leur code de hachage doit être identique.
```
------------------------------------------
**gestion des fichiers**
```java
public static void testWrite() throws IOException {
    File file = new File("test.txt");
    PrintWriter out = new PrintWriter(file);
    out.print("PRO2 : ");
    out.println("Ecriture dans un fichier.");
    out.close();
}
PrintWriter out = null;
try {
    out = new PrintWriter(new File("test.txt"));
    out.println("TEST");
} catch (IOException exception) {
    //...
} finally {
    if (out != null) {
        out.close();
    }
}
public static void testRead() throws IOException {
    File file = new File("test.txt");
    Scanner in = new Scanner(file);
    while(in.hasNext()) {
        String token = in.next();
        System.out.println(token);
    }
    in.close();
}
```
------------------------------------------
**Boxing / Inboxing**
```java
Integer x = new Integer(2);
Integer y = 12; //boxing
int z = x; //unboxing de x
```
------------------------------------------
**Interfaces**

Collections.sort(List<E>) trie une liste d'éléments comparables (qui
implémentent donc l'interface Comparable<E>).
```java
public interface Scalable {
    int MY_CONST = 42; // équivalent de : public static final int MY_CONST = 42;
    void scale(double scaleFactor); // équivalent de : punlic abstract void scale(double scaleFacor);
}
public class Rectangle implements Scalable {
    private double height;
    private double width;
    public double getWidth() {
        return width;
    }
    public double getHeight() {
        return height;
    }
    @Override
    public void scale(double scaleFactor) {
        height *= scaleFactor;
        width *= scaleFactor;
    }
}
```
--Comparable
```java
public class Date implements Comparable<Date>{
    @Override
    public int compareTo(Person p) {
        return -this.dateOfBirth.compareTo(p.dateOfBirth);
    }
}
public interface Comparable<T> {
    public int compareTo(T o);
}
// Collections.sort(List<E>) trie une liste d'éléments comparables.
```
```java
private final Set<Reservation> reservationList = new TreeSet<>((o1, o2) -> {
    int cmp = o1.getPizzeria().toString().compareTo(o2.getPizzeria().toString());
    if (cmp == 0) {
        cmp = Integer.compare(o1.getNumberOfPeople(), o2.getNumberOfPeople());
        if (cmp == 0) {
            cmp = Integer.compare(o1.getId(), o2.getId());
        }
    }
    return cmp;
});
```
------------------------------------------
**Class anonyme**
```java
reservationList.sort(new Comparator<Reservation>() {
    @Override
    public int compare(Reservation o1, Reservation o2) {
        int cmp = o1.getPizzeria().toString().compareTo(o2.getPizzeria().toString());
        if (cmp == 0) {
            cmp = Integer.compare(o1.getNumberOfPeople(), o2.getNumberOfPeople());
            if (cmp == 0) {
                cmp = Integer.compare(o1.getId(), o2.getId());
            }
        }
        return cmp;
    }
    });
```
------------------------------------------        
**Class Abstract**
```java
public abstract class Shape {
    Point origin;
    /*
    public Shape(Point origin) {
        this.origin = origin;
    }
    */
    public void translate(int dx, int dy) {
        origin.moveTo(origin.getX() + dx, origin.getY() + dy);
    }
    public abstract double getArea();
}
public class Square extends Shape {
    private int width;
    public Square(Point topLeft, int width) {
        /*
        Si la classe asbtraite a un constructeur : 
        super(topLeft);
        */
        this.origin = topLeft;
        this.width = width;
    }
    @Override
    public double getArea() {
        return width * width;
    }
    public int getWidth() {
        return width;
    }
}
// Un membre public est visible PARTOUT.
// Un membre private est visible :
//  - Accessible uniquement dans la classe où il est déclaré.
// Pas accessible :
//  - par les sous-classes.
//  - par les classes du même package.

// Un membre default est visible :
//  - seulement dans le même package.
// Pas accessible :
//  - hors du package.
//  - même par une sous-classe dans un autre package.

// Un membre protected est visible :
//  - dans la classe elle-même.
//  - dans toutes les classes du même package.
//  - dans les sous-classes, même si elles sont dans un autre package
//   MAIS uniquement via l’héritage (pas via une instance arbitraire)
/*
On choisit d'hérité une classe normale quand :
Quand la classe parente est complète et utilisable telle quelle.
    - Elle peut exister seule
    - Elle a une implémentation complète
    - La classe fille est juste une spécialisation
    - Tu ne veux pas forcer les enfants à redéfinir des méthodes

On choisit d'hérité une classe abstraite quand : 
Quand la classe est une idée / un concept, pas un objet concret.  
    - Elle ne doit pas être instanciée
    - Elle définit un contrat partiel 
    - Tu veux forcer les sous-classes à implémenter certaines méthodes
    - Tu veux partager du code commun + règles 
*/
```
------------------------------------------
**Copy**
```java
// En surface :
/*
Dans une copie en surface,quand les champs/attributs sont des primitives (ou immuables) [int;double;boolean;char], 
je n'ai pas besoin de faire new (..) durant l'assignation des attributs this(new..) dans le contructeur qui s'occupe de copier.
Ex : 
class Item {
    private final String name ;
    private double price ;
    Item ( String name , double price ) {
        this . name = name ;
        this . price = price ;
    }
    Item ( Item original ) { 
        this ( original.name , original.getPrice ()); 
    }
    String getName () { 
        return name ; 
    }
    double getPrice () { 
        return price ; 
    }
    void setPrice ( double price ) { 
        this . price = price ; 
    }
    @Override
    public String toString () { 
        return getName () + " , " + getPrice (); 
    }
}

class ExpensiveItem extends Item {
    ExpensiveItem ( String name , double price ) { 
        super ( name , price ); 
    }
    ExpensiveItem ( ExpensiveItem original ) {
        this ( original . getName ()+ " _copy " , original . getPrice ());
    }
    double getPrice () { 
        return super . getPrice () * 2; 
    }
}
ExpensiveItem expItem1 = new ExpensiveItem ( " TV " , 1000);
Item copy = new Item ( expItem1 );

original.name → on recopie la valeur (String)
original.getPrice() → on recopie un double (une valeur)
On copie la valeur, pas une référence.
Modifier l’un ne touche jamais l’autre.
*/
public class Car {
    private Person owner;
    private String model;
    public Car(Person owner, String model) { … }
    public Car(Car original) {
        this(original.owner, original.model);
    }
}
// En profondeur : 
public class Car {
    private Person owner;
    private String model;
    public Car(Person owner, String model) { … }
    public Car(Car original) {
        this(new Person(original.owner),
        original.model);
    }
}
```
------------------------------------------
**Iterable**
```java
class Boite implements Iterable<String> {
    private List<String> elements = Arrays.asList("Pomme", "Banane", "Poire");
    @Override
    public Iterator<String> iterator() {
        return elements.iterator(); // renvoie l’itérateur de la liste
    }
}
public class ExempleIterable {
    public static void main(String[] args) {
        Boite maBoite = new Boite();

        // 🔸 Utilisation d’un Iterator
        Iterator<String> it = maBoite.iterator();
        while (it.hasNext()) {
            String fruit = it.next();
            System.out.println(fruit);
        }

        // 🔸 Utilisation d’une boucle foreach
        for (String fruit : maBoite) {
            System.out.println(fruit);
        }
    }
}
```
++++++++++++++++++++++++++++++++++++++++++++++
```java
class Range implements Iterable<Integer> {
    private final int start;
    private final int end; // exclusif

    Range(int start, int end) {
        this.start = start;
        this.end = end;
    }

    @Override
    public Iterator<Integer> iterator() {
        return new Iterator<Integer>() {
            private int current = start;

            @Override
            public boolean hasNext() {
                return current < end;
            }

            @Override
            public Integer next() {
                int cour = current
                current ++;

                return cour;
            }
        };
    }
}
public static void main(String[] args) {
    ArrayList<String> liste = new ArrayList<>();
    liste.add("Java");
    liste.add("2026");

    // Récupération de l'itérateur
    Iterator<String> it = liste.iterator();

    // Boucle de parcours
    while (it.hasNext()) {
        String element = it.next();
        System.out.println(element);
        
        // Suppression sécurisée si besoin
        if (element.equals("Java")) {
            it.remove(); 
        }
    }
}
```
++++++++++++++++++++++++++++++++++++++++++++++
```java
public class Test {
    public static void main ( String [] args ) { 
        Date deb = new Date (30 , 12 , 2017); 
        Date fin = new Date (2 , 1 , 2018); 
        for ( Date d : new DateRange ( deb , fin )) 
        System . out . println ( d ); 
        /*
        Devrait afficher ...
        Samedi 30 Décembre 2017
        Dimanche 31 Décembre 2017
        Lundi 1 Janvier 2018
        Mardi 2 Janvier 2018
        mais affiche ...
        Dimanche 31 Décembre 2017
        Lundi 1 Janvier 2018
        Mardi 2 Janvier 2018
        Mercredi 3 Janvier 2018
        */
    }
} 
class DateRange implements Iterable < Date > { 
    Date deb , fin ; 
    public DateRange ( Date deb , Date fin ) {
        this . deb = deb ;
        this . fin = fin ;
    }
    @Override 
    public Iterator < Date > iterator () {
        return new DateIterator ( this );
    }
}
class DateIterator implements Iterator < Date > {
    DateRange dr ;
    Date cour ;
    DateIterator ( DateRange dr ) {
        this . dr = dr ;
        cour = dr . deb ;
    }
    @Override 
    public boolean hasNext () {
        return cour . compareTo ( dr . fin ) <= 0;
    }
    @Override 
    public Date next () {
        /*
        Date res = cour ;
        cour . increment();
        return res ;
        */
        Date res = new Date(cour.getDay(),cour.getMonth(),cour.getYear())
        cour.increment();
        return res;
    }
}
```
------------------------------------------
**Enum**
```java
public enum Suit {
    CLUB, DIAMOND, HEART, SPADE;
}
Suit suit = Suit.HEART;
System.out.println(suit.name()); // HEART
System.out.println(suit.ordinal()); // 2
if(suit == Suit.HEART) {
    // les valeurs d'une enumération sont des constantes,
    // on peut donc les comparer avec ==
}
for(Suit s : Suit.values()) {
    System.out.println(s.ordinal() + " " + s.name());
    // 0 CLUB
    // 1 DIAMOND
    // 2 HEART
    // 3 SPADE
}
```
```java
public enum StreamingQuality {
    LOW(10.99),
    HIGH(15.99);
    private final double price;
    StreamingQuality(double price) {
        this.price = price;
    }
    public double getPrice() {
        return price;
    }
}
```
Utilisation : 
StreamingQuality stream = StreamingQuality.LOW;
System.out.println(stream.LOW); // 10.99
------------------------------------------
**Switch**
```java
public int endOfMonth(int monthValue,int year){
    int days;
    switch (monthValue) {
        case 1:
        case 3:
        case 5:
        case 7:
        case 8:
        case 10:
        case 12: days = 31;
            break;
        case 2: days = isLeapYear(year) ? 29 : 28;
            break;
        default: days = 30;
    }
    return days;
}
```
```java
int days = switch (monthValue) {
    case 1, 3, 5, 7, 8, 10, 12 -> 31;
    case 2 -> isLeapYear(year) ? 29 : 28;
    default -> 30;
};
```