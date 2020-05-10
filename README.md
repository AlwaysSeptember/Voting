# Voting

The PHP project has shown that voting is a good way of resolving situations where there are differences of opinion that can't be resolved.

However, the implementation for voting used in the PHP project has several drawbacks that should be addressed.


## Draft of votes + pull requests

One of the issues with the PHP implementation is that the only way to suggest changes to a proposed vote is through email. 

Suggesting changes through pull requests is much easier on both the person suggesting the change, and the person who proposed the vote.


## Scheduled + automated

The opening and closing of votes should be done automatically according to the schedule inside the proposed vote.

Currently people can start votes unexpectedly and choose when to close them. Both of these can lead to drama and so should be avoided where possible.

## Distribute voting time

Having a scheduled close time makes an existing vulnerability in voting be even more exploitable.

Imagine a vote looked like it was going to fail. If a large number of voters left it until the last moment to cast their vote, they could change the outcome of a vote, and not leave enough time for people to respond.

Note, people sometimes don't vote to avoid the appearance of 'piling on', when an idea is already being rejected soundly.


### Better voting systems

There are many different types of [voting systems](https://en.wikipedia.org/wiki/Electoral_system). In some cases, being able to vote through [Single transferable vote](https://en.wikipedia.org/wiki/Single_transferable_vote) is likely to provide a better result than the single vote PHP uses now.


## Vote types

Each room should define it's own set of vote types. For PHP this could be 'RFC', 'release manager', 'indication of interest'. 
 
The exact rules for who should have voting rights, how far in advance votes should be announced, and how long they need to remain open would also be up to each room to decide.


## Vote format

The format used for defining votes could look something like this:

```
<vote>
  <type>RFC</type>
  <start_datetime>2020-05-10T06:04:04+00:00</start_datetime>
  <close_datetime>2020-05-17T06:04:04+00:00</close_datetime>
  <questions>
    <question>
      <text>Add mixed as a type to be used as parameter, return and class property types?</text>
      <system>stv</system>
      <options>  
        <option>Yes</option>
        <option>Meh</option>
        <option>No</option>
      </options>
    </question>
  </questions>
</vote>
```


Where:

* type - the type of vote that is occuring. For PHP this could be 'RFC', 'release manager'. 

* start_datetime - the proposed time that the vote should open.

* close_datetime - the planned time that the vote should close.

* questions - a list of the proposed questions. Each of them should have:

** text - the text should would be shown as the thing being voted on.

** system - what voting system should be used. e.g. single transferable vote, first past the post.

** options - the list of choices for that question.


## Publishing of vote results

This is a nice to have option. The site https://php-rfc-watch.beberlei.de/ shows a desire for people to be able to see decisions made.



