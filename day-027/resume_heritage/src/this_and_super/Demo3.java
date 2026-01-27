package this_and_super;

public class Demo3 {
    static class Item {
        private final String name ;
        private double price ;
        Item ( String name , double price ) {
            this . name = name ;
            this . price = price ;
        }
        Item ( Item original ) { this ( original . name , original . getPrice ()); }
        String getName () { return name ; }
        double getPrice () { return price ; }
        void setPrice ( double price ) { this . price = price ; }
        @Override
        public String toString () { return getName () + " , " + getPrice (); }
    }
    static class ExpensiveItem extends Item {
        ExpensiveItem ( String name , double price ) { super ( name , price ); }
        ExpensiveItem ( ExpensiveItem original ) {
            this ( original . getName ()+ " _copy " , original . getPrice ());
        }
        double getPrice () { return super . getPrice () * 2; }
    }
    public class Pgm {
        public static void main ( String [] args ) {
            ExpensiveItem expItem1 = new ExpensiveItem ( " TV " , 1000);
            Item copy = new Item ( expItem1 );
            ExpensiveItem expItem2 = new ExpensiveItem ( expItem1 );
            System . out . println ( expItem1 );
            System . out . println ( copy );
            System . out . println ( expItem2 );
            expItem1 . setPrice (3000);
            System . out . println ( expItem1 );
            System . out . println ( copy );
            System . out . println ( expItem2 );
        }
    }
}
