package scratch.model;

public class Segment {
    private Point start;
    private Point end;

    public Segment(Point start, Point end){
        this.start = start;
        this.end = end;
    }

    public Point getStart() {
        return this.start;
    }
    public Point getEnd(){
        return this.end;
    }

    public void setStart(double x, double y){
        this.start.setX(x);
        this.start.setY(y);
    }
    public void setEnd(double x, double y){
        this.end.setX(x);
        this.end.setY(y);
    }
}
