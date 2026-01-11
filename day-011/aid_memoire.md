Syntaxe : 
===========================================
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
------------------------------------------
System.out.print("Entrez 3 entiers séparés par des espaces (JJ MM YYYY) : ");
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
------------------------------------------
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
------------------------------------------
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
------------------------------------------
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
------------------------------------------
Integer x = new Integer(2);
Integer y = 12; //boxing
int z = x; //unboxing de x