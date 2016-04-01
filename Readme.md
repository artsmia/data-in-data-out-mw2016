# Anything In, Anything Out

**Dynamic Data Aggregation + Web Publishing**

---

We take data, convert it to JSON, store it in Redis, index it in ElasticSearch and then publish it for the masses. Here's how...

TODO: a drawing?

## Setup

We've prepared an AWS machine for everyone to use with the required data
and tools at the ready.

You'll need to open a terminal and use `ssh` to connect to it:

`ssh ubuntu@<ip address>`

If you want, you can `git clone` this repository and follow along on your
local machine.

## Lesson 1: Where does data come from?

### Introduction to data at Mia.

Data lives in different places and formats. For this workshop we will focus on collections data.

### Converting data into JSON.

JSON is a swiss army knife data representation. It's replaced
XML as the common-denominator format of data returned from APIs.

### API it.


## Lesson 2: Fun with Redis!

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

`hmset` can set multiple hash values in one command: `hmset artist:van-gogh birth "30 March 1853" death "29 July 1890"`

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

TODO cover sets and relating things by key within redis?

### Understanding Key Value stores, and how to make them work for you.

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

To get around the slowdown from saving thousands of `key: value` pairs,
we group artworks by their object id and store them in a series of
hashes.

We call these "**buckets**". Artworks are sorted into buckets
according to their object ID. The first 1000 go into "bucket 0", the
next 1000 go into "bucket 1".

---

To make it easy to know which bucket an object goes in, we use the
object id divided by 1000 to assign buckets.

| Object ID | Bucket |
| ---  | --- |
| `id` | `id / 1000` |
| 278 | 0 |
| 1218 | 1 |
| 60728 | 60 |

Each bucket is stored in a redis hash. Here's how to get the info on object
60728:

```
> hget object:60 60728
"{\"id\":\"60728\",\"title\":\"Celestial Horse\",\"medium\":\"Bronze with traces of polychrome\", <... lots of JSON>}"
```

---

### Questions?

## Lesson 3: Indexing and searching data

(Or, **Where did I put my keys?!**)

---

### Redis doesn't do search.

It's great for storing data and accessing it quickly, but redis needs
know exactly what you're looking for.

[Elasticsearch](https://www.elastic.co/products/elasticsearch) is a tool
for *information retrieval*. It's great at finding things.

---



## Lesson 4: Search for it, I dare you.

Included in this repository is a simple php site that allows you to enter a search term and then renders the full JSON found in Redis via ElasticSearch and a styled version of the same data.

Things to note: Load times,  UI flexibility.

## In Conclusion

## Resources

# Ideas / TODO

install and run kibana
