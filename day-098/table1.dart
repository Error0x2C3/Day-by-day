class Table {
  final int id;
  final int tableNumber;
  final int capacity;

  Table({
    required this.id,
    required this.tableNumber,
    required this.capacity,
  });

  factory Table.fromJson(Map<String, dynamic> json) {
    return Table(
      id: json['id'],
      tableNumber: json['table_number'],
      capacity: json['capacity'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'table_number': tableNumber,
      'capacity': capacity,
    };
  }

  Table copyWith({
    int? id,
    int? tableNumber,
    int? capacity}) {
    return Table(
      id: id ?? this.id,
      tableNumber: tableNumber ?? this.tableNumber,
      capacity: capacity ?? this.capacity,
    );
  }

  @override
  String toString() {
    return '(id: $id, tableNumber: $tableNumber, capacity: $capacity)';
  }
}