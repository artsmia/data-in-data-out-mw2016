# Anything In, Anything Out: Dynamic Data Aggregation to Web Publishing

We take data, convert it to JSON, store it in Redis, index it in ElasticSearch and then publish it for the masses. Here's how...

## Getting Setup:
  Clone this repository! Your machine should have the following packages installed:


## Lesson 1: Where does data come from?
  ### Introduction to data at Mia.
  We, like many cultural institutions, have data in various places, in all formats. For this workshop we will be focusing on collections data. We store our collections meta data in TMS with visual resources stored in Media. We have over 90,000 objects in our collection. Lots of data!
  We have included a sample of our data in this repository to be experimented with through out the demonstration.

  ### Converting Data into JSON.
  We encourage you to convert your data into JSON format. This is not required for the following steps to work at your organization. Our system/structure for storing and returning data dynamically will work with all data formats.
  We prefer JSON for a couple of reasons:
    1. More flexible. We think JSON is by far the most flexible when publishing to the web.
    2. It works with all kinds of front end content management and web publishing systems.
    3. It's easy to understand, even for normal people.
    4. We like it.
  Here are a couple ways to convert your data or any data into json format:

  ### API it.


## Lesson 2: Fun with Redis!
    ### What is Redis?
      Redis is like a new and improved version of Memcache. Redis is a key value store database that is crazy fast and extremely flexible.
      [Redis.io](http://Redis.io)
      Let's run a few basic commands in the terminal!

    ### Understanding Key Value stores, and how to make them work for you.
      Deciding on a structure for your data in Redis is often times the hardest part.
      This is how we do it for our collections data:
        We use buckets. Each object in our collection has a unique id, a minimum of 3 digits. We group them based on all digits preceding the last two: object 1230 would be part of group 12. object 43290 would be part of group 432. By sectioning them into buckets we are able to search through a smaller set based on their group number. instead of searching through all 90,000+ ids for a single object.
        These groups and the structure in Redis has no association to the object it is merely a way to break the data into manageable sets.
        Here is a sample of our data structure:

        Things to think about when deciding on a structure for your data inside of Redis:
          Associations. How can you structure the data to take advantage of Redis's ability to create unions, sets, and other native functions?
          Future possibilities. Can you see anything n the near or distant future that may prove useful to how you structure data now?

    ### Structure? Check! Let's put it in Redis.
      You have your JSON data. Let's save it to Redis.
      Run the following commands:

    ### Publishing data directly from Redis using PHP.


## Lesson 3: Where did I put my keys?!
  ### ElasticSearch can save users
    We index our data in ElasticSearch.

    **Here's why:**

    **Here's How:**

## Lesson 4: Search for it, I dare you.
    Included in this repository is a simple php site that allows you to enter a search term and then renders the full JSON found in Redis via ElasticSearch and a styled version of the same data.
    Things to note:
      Load times.
      UI flexibility.
## In Conclusion

## Resources
