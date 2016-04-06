# Anything In, Anything Out

**Dynamic Data Aggregation + Web Publishing**

---

## Introductions

We are the 'software' team at the Minneapolis Institute of Art

* Andrew David
* Misty Havens
* Kjell Olsen

You:

* where are you from?
* What's are you hoping to learn
* if you could have any one superpower, what would it be?

???

We take data, convert it to JSON, store it in Redis, index it in ElasticSearch and then publish it for the masses. We're here to show you how...

Workshop attendee intros

---

# Lesson 1: Where does data come from?
<img src="img/where-data.gif" alt="Where data?" style="width: 500px;"/>

### Introduction to data at Mia.

<img src="img/Mia_site_structure.jpg" alt="map of data at Mia" style="width: 100%;"/>

???

Data lives in different places and formats. For this workshop we will focus on collections data.

### Converting data into JSON.

JSON is a swiss army knife data representation. It's replaced
XML as the common-denominator format of data returned from APIs.

---

```
{
  "accession_number": "2015.79.83.1",
  "artist": "Ogata Kenzan",
  "continent": "Asia",
  "country": "Japan",
  "creditline": "Mary Griggs Burke Collection, Gift of the Mary and Jackson Burke Foundation",
  "culture": null,
  "dated": "first half 18th century",
  "department": "Japanese and Korean Art",
  "description": "pink, red, and white hollyhocks in various stages of blossom; bottom stems of blossoming hollyhocks also in ULQ",
  "dimension": "43 1/2 × 113 in. (110.49 × 287.02 cm) (image)\r\n45 × 114 1/2 × 5/8 in. (114.3 × 290.83 × 1.59 cm)",
  "id": "122147",
  "image": "invalid",
  "image_copyright": "",
  "image_height": "",
  "image_width": "",
  "inscription": "Signature and Seal",
  "life_date": "Japanese, 1663 - 1743",
  "markings": "LLC red artist\\'s seal: {Reikai}",
  "medium": "Ink and color on gilded paper",
  "nationality": "Japanese",
  "provenance": "Prince Komatsu",
  "restricted": 0,
  "rights_type": "Public Domain",
  "role": "Artist",
  "room": "G219",
  "signed": "LLC: (Painted by the Eremite from the Flowering Capital Shisui Shinsei at age 81)",
  "style": "18th century",
  "text": "",
  "title": "Hollyhocks"
}
```

???

"artisinal json": have attendees hand-build a JSON representation of
what their API would look like

## Setup

We've prepared an AWS machine for everyone to use with the required data
and tools at the ready.

You'll need to open a terminal and use `ssh` to connect to it:

TODO distribute pem

`ssh ubuntu@<ip address>`

If you want, you can `git clone` this repository and follow along on your
local machine.

# Lesson 2: Fun with Redis!

### What is Redis?

[Redis](http://redis.io) is a key value store that is fast and flexible. It does simple `key: value` mappings, but beyond that it is also a "data structure store", optimized to handle different types of data quickly and effectively.

---

The basics:

* strings store a single value with the key used to retrieve it: `set name kjell` or `set day friday!`

* hashes can store multiple values behind a single key:

```
hset artist:van-gogh firstName Vincent
hset artist:van-gogh lastName "Van Gogh"
```

---

`hmset` can set multiple hash values in one command:

`hmset artist:van-gogh birth "30 March 1853" death "29 July 1890"`

---

So now our data for Van Gogh looks like:

```
> hgetall artist:van-gogh
1) "firstName"
2) "Vincent"
3) "lastName"
4) "Van Gogh"
5) "birth"
6) "30 March 1853"
7) "death"
8) "29 July 1890"
```

Try adding an artist to your local redis database.

---

a **set** is a list of things. Let's use one to index our artists:

`sadd artists artist:van-gogh` adds "Van Gogh" to new set called artists.

Then add the artist you created.

---

You can use this list to keep track of all the artists you've stored in
redis.

```
127.0.0.1:6379> smembers artists
1) "artist:van-gogh"
2) "artist:monet"
...
```

For example, and API endpoint to return all artists would

1. ask redis for all artist keys (`smembers artists`)
2. ask redis for the information stored in each artist's record
   (`artists.map { |artist| hgetall artist:id }`)
3. Transform that information into JSON

```
{
  "van-gogh": {
    "firstName": "Vincent",
    "lastName": "Van Gogh",
    ...
  },
  "monet": {
    ...
  }
}
```

## Understanding Key Value stores, and how to make them work for you.

Deciding on a structure for your data in Redis is the hardest part.

For representing museum object data, the simplest solution would be to
store information about each object using the object `id` as a key. So
to look up information on `17`, I would say to redis:

```
> get object:17
"{\"id\":\"17\",\"title\":\"Sketch made on Indian Reservation, Montana\",\"medium\":\"Graphite\",…}"
```

---

But as the size of the `object:` keys grows, it takes longer and longer
for redis to keep things organized. This is where the *data structure*
part of redis comes into play.

To get around the issues of saving thousands of `key: value` pairs,
we group artworks by their object id and store them in a series of
hashes. Hashes are much more memory efficient [Redis Opimizations](http://redis.io/topics/memory-optimization).

To store our hashes we organize them in "**buckets**". Artworks are sorted into buckets
according to their object ID. The first 1000 go into "bucket 0", the
next 1000 go into "bucket 1" and so on.  This helps improve the memory footprint and keeps retrieval time down.

---

To make it easy to know which bucket an object goes in, we use the
object id divided by 1000 to assign buckets.

| Object ID | Bucket |
| ---  | --- |
| `id` | `id / 1000` |
| 278 | 0 |
| 1218 | 1 |
| 60728 | 60 |

Each bucket is stored in a redis hash and associated with its unique object id. Here's how to get the info on object
60728:

```
> hget object:60 60728
"{\"id\":\"60728\",\"title\":\"Celestial Horse\",\"medium\":\"Bronze with traces of polychrome\", <... lots of JSON>}"
```

---

# Questions?
<img src="img/questions.gif" alt="Raise your hand" style="width: 500px;"/>

---

### Redis doesn't do search.

It's great for storing data and accessing it quickly, but redis needs
know exactly what you're looking for.

[Elasticsearch](https://www.elastic.co/products/elasticsearch) is a tool
for *information retrieval*. It's great at finding things.

---

# Lesson 3: Indexing and searching data

(Or, **Where did I put my keys?!**)

### Indexing

Elasticsearch uses something called an [**inverted
index**](https://www.elastic.co/guide/en/elasticsearch/guide/current/inverted-index.html)
to to build a searchable representation of a set of **documents**.

???

It breaks the documents given to it down into their component words
(analysis), then stores those words in an index along with a link to the
documents containing them.

---

Looking at three example "documents",

1: Chariot Finial with Bird  
2: Xi Bi Disc  
3: Bi Scabbard Chape  
4: Bi (Ceremonial Disk Symbolizing Heaven)  
5: Bi (Cermonial Disk Symbolizing Heaven)  

---

Let's use "Chariot Finial with Bird" (1) as an example of analysis. The
default analyzer (`standard`) *tokenizes* the full string into one or
more tokens (words), then lowercases each of those words and removes
common *stopwords* such as "the", "with", and "as":

"Chariot Finial with Bird" => `chariot finial bird`

For all 5 documents, the inverted index will look like…

---

| term | document ids |
| --- | --- |
| chariot | 1 |
| finial | 1 |
| bird | 1 |
| xi | 2 |
| bi | 2, 3, 4, 5 |
| disc | 2, 4, 5 |
| scabbard | 3 |
| chape | 3 |
| ceremonial | 4 |
| cermonial | 5 |
| symbolizing | 4, 5 |
| heaven | 4, 5 |

```
1: Chariot Finial with Bird  
2: Xi Bi Disc  
3: Bi Scabbard Chape  
4: Bi (Ceremonial Disk Symbolizing Heaven)  
5: Bi (Cermonial Disk Symbolizing Heaven)  
```

???

Elasticsearch responds to a search by breaking it into individual
words, and matching those words to documents using this inverted index.

So a search for 'bi' would match documents 2, 3, 4, 5.

'bi AND heaven': 4, 5

'xi AND bi': 2

'xi OR bi': 2, 3, 4, 5 (note, here 2 is unequivocally the "top" hit
because it contains both of the searched terms, where 3,4,5 only contain
1)

'bi AND disc': 2, 3, 5, 4

### Documents, mapping, analysis

Elasticsearch uses a very flexible indexing process. When it gets new
information, it tried to guess what that information is comprised of.

For instance, our representation of Van Gogh from earlier contained two
strings for name (first, last) and two dates (birth, death).

Elasticsearch needs to know that the name fields are of type string and
the date fields are type date.

???

Elasticsearch can infer some data types, such as strings and numbers.

Mappings can also be specified when creating an index. These mappings
can specify advanced analysis of a field.

---

#### A few examples of mappings we use:

`"accession_number": { "type": "string", "index": "not_analyzed" }`

```
"department": {
  "type": "string",
  "fields": {
    "raw": {"type": "string", "index": "not_analyzed" }
  }
}
```

```
"title": {
  "type": "string",
  "analyzer": "snowball",
  "fields": {
    "raw": { "type": "string", "index": "not_analyzed" },
    "ngram": {"type": "string", "analyzer": "ngram" },
    "sort": {"type": "string", "analyzer": "lowercase_sort"}
  }
}
```

???

1. Don't analyze accession numbers. `L2011.43.6.a-b` should be indexed
exactly like that and not broken up into tokens.

2. This indexes `department` twice: once with the standard settings, and
then again as `department.raw` as an un-alayzed string.

3. For artwork titles, index them with the "snowball" analyzer by
   default. Index 3 subfields, "raw" as an untouched string, "ngram" for
   searching on parts of words, and sort

"Snowball" does stemming: `horses` => `horse`

# Lesson 4: Getting data out and displaying data

---

<img src="img/prototype.jpg" alt="prototype" style="width: 100%;"/>

???

Included in this repository is a simple php site that allows you to enter a search term and then renders the full JSON found in Redis via ElasticSearch and a styled version of the same data.

Examples of the benefits of mappings. -- KO

Favoriting and seeing the data in redis. --MH

---

# In Conclusion

---

# Resources
