package scratch.model;

/*
représente l'état actuel de la tortue dans la scène;

    Ses informations à l'instant T ( position,degré(s) et PenDown ).
    Les Segments qu'elle a tracée jusqu'à mtn.
 */
public class ExecutionContext {
    private Cursor cursor;
    private Canvas canvas;
    public ExecutionContext(Cursor cursor, Canvas canvas){
        this.cursor = cursor;
        this.canvas = canvas;
    }

    public ExecutionContext(){
        // Crée un terrain neuf (sans segment).
        this.canvas = new Canvas();
        // Crée la tortue directement au centre par défaut et abaissé.
        this.cursor = new Cursor();
    }
    public Cursor getCursor() {return this.cursor;}
    public Canvas getCanvas(){return this.canvas;}

    public void reset(){
        // Efface tous les segments tracés par la tortue.
        this.canvas.clear();
        // Remet la tortue à son état de départ : au centre (position 0,0), angle à 0 degré et stylo abaissé (true).
        this.cursor.setPosition(0,0);
        this.cursor.setHeading(0.0);
        this.cursor.setPenDown(true);
    }
}

