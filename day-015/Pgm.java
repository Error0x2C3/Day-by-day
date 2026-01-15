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
