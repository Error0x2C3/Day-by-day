package labo15.ex03;

import java.util.Objects;

public class Person implements Comparable<Person> {
    private String firstName;
    private String lastName;
    private Date dateOfBirth;
    
    public Person(String firstName, String lastName, 
            int day, int month, int year) {
        this.firstName = firstName;
        this.lastName = lastName;
        this.dateOfBirth = new Date(day, month,year);
    }
    
    public int getAge() {
        Date today = new Date();
        int age = today.getYear() - dateOfBirth.getYear();
        if (today.getMonth() < dateOfBirth.getMonth()
                || (today.getMonth() == dateOfBirth.getMonth() 
                    && today.getDay() < dateOfBirth.getDay())) {
            age--;
        }
        return age;
    }
    
    @Override
    public String toString(){
        return firstName + " " + lastName + " age: " + getAge();
    }
    
    @Override
    public boolean equals(Object o) {
        if(o instanceof Person) {
            Person p = (Person)o;
            return lastName.equals(p.lastName) &&
                    firstName.equals(p.firstName) &&
                    getAge() == p.getAge();
                    
        }
        return false;
    }
    
    @Override
    public int compareTo(Person o) {
        int cmpLastName = lastName.compareTo(o.lastName);
        if(cmpLastName != 0)
            return cmpLastName;
        else {
            int cmpFirstName = firstName.compareTo(o.firstName);
            if(cmpFirstName != 0) {
                return cmpFirstName;
            }
            else {
                return this.getAge() - o.getAge();
            }
        }
    }
    @Override
    public int hashCode(){
        return Objects.hash(this.firstName,this.lastName,this.getAge());
    }
}
