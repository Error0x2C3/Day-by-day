class Item {
    private final String name ;
    private double price ;
    Item ( String name , double price ) {
    this . name = name ;
    this . price = price ;
    }
    Item ( Item original ) { 
        this ( original . name , original . getPrice ()); 
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
public class Pgm {
    public static void main ( String [] args ) {
    ExpensiveItem expItem1 = new ExpensiveItem ( " TV " , 1000);
    Item copy = new Item ( expItem1 );
    ExpensiveItem expItem2 = new ExpensiveItem ( expItem1 );

    System . out . println ( expItem1 ); // Rep : TV,2000
    System . out . println ( copy ); // Rep : TV, 2000
    System . out . println ( expItem2 ); // Rep : TV_copy, 40000
    expItem1 . setPrice (3000);
    System . out . println ( expItem1 ); // Rep : TV,6000
    System . out . println ( copy ); // Rep : TV,2000
    System . out . println ( expItem2 ); // Rep : TV_copy, 4000
    }
}

// En surface :
/*
Dans une copie en surface,quand les champs/attributs sont des primitives (ou immuables) [int;double;boolean;char],
tu n’as PAS besoin de faire new dans une copie.
Ex :
class Item {
private final String name ;
private double price ;
Item ( String name , double price ) {
this . name = name ;
this . price = price ;
}
Item ( Item original ) {
this ( original . name , original . getPrice ());
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