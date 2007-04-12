Doctrine Query Language(DQL) is an Object Query Language created for helping users in complex object retrieval.
You should always consider using DQL(or raw SQL) when retrieving relational data efficiently (eg. when fetching users and their phonenumbers).
<br \><br \>
When compared to using raw SQL, DQL has several benefits: <br \>
    <ul>
    <li \>From the start it has been designed to retrieve records(objects) not result set rows
    </ul>
    <ul>
    <li \>DQL understands relations so you don't have to type manually sql joins and join conditions
    </ul>
    <ul>
    <li \>DQL is portable on different databases
    </ul>
    <ul>
    <li \>DQL has some very complex built-in algorithms like (the record limit algorithm) which can help
    developer to efficiently retrieve objects
    </ul>
    <ul>
    <li \>It supports some functions that can save time when dealing with one-to-many, many-to-many relational data with conditional fetching.
    </ul>

If the power of DQL isn't enough, you should consider using the rawSql API for object population.


<code type="php">
// DO NOT USE THE FOLLOWING CODE 
// (using many sql queries for object population):

$users = $conn->getTable('User')->findAll();

foreach($users as $user) {
    print $user->name."<br \>";
    foreach($user->Phonenumber as $phonenumber) {
        print $phonenumber."<br \>";
    }
}

// same thing implemented much more efficiently: 
// (using only one sql query for object population)

$users = $conn->query("FROM User.Phonenumber");

foreach($users as $user) {
    print $user->name."<br \>";
    foreach($user->Phonenumber as $phonenumber) {
        print $phonenumber."<br \>";
    }
}

</code>
