---
name: Cards (EXAMPLE! DO NOT USE)
category: Components
---

# C:01 - Cards

Cards are used to isolate pieces of content. They can be fully clickable, or
they can simply block out content for clear separation.

Vards are build using the `VCard.vue` component and take the folloing properties:

| Prop     | Type   | Required | Default |                                 Info |
| :---     | :---   |     ---: |    ---: |                                 ---: |
| article  | Object |     true |         |          This is the article content |
| cardType | String |    false |    news | The type of card, news, insights etc |


## Card | News

Generic cards are the most basic form - They include at least a `__title` and
`__text`, but may also include a `__subtitle` where required.

```card-news.html
<div class="card card--news">
  <a href="this-is-a-longer-title" class="card__link">
    <picture class="responsive-image">
      <source srcset="https://picsum.photos/150/150/?random" type="image/png" media="screen and (max-width: 150px)">
      <source srcset="https://picsum.photos/250/124/?random" type="image/png" media="screen and (max-width: 250px)">
      <source srcset="https://picsum.photos/768/380/?random" type="image/png" media="screen and (max-width: 768px)">
      <source srcset="https://picsum.photos/700/346/?random" type="image/png" media="screen and (max-width: 700px)">
      <source srcset="https://picsum.photos/120/59/?random" type="image/png" media="screen and (max-width: 120px)">
      <source srcset="https://picsum.photos/700/200/?random" type="image/png" media="screen and (max-width: 700px)">
      <source srcset="https://picsum.photos/700/346/?random" type="image/png" media="screen and (min-width: 1401px)">
      <img class="card__image" src="https://picsum.photos/700/346/?random">
    </picture>
    <div class="card__content">
      <div class="card__meta">02 October 2018 • post</div>
      <h2 class="card__title">This is a longer title to test what we can do to enforce a limit …</h2>
      <h3 class="card__subtitle"></h3>
      <p class="card__text">Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestib…</p>
    </div>
  </a>
</div>
```

## Card | Insights

Cards can be clickable, in this instance an `<a>` tag is used, and a modifier of `--clickable` is added.
This now gives the card a nice visual effect to indicate interaction.

```card-insights.html
<div class="card card--news">
  <a href="this-is-a-longer-title" class="card__link">
    <picture class="responsive-image">
      <source srcset="https://picsum.photos/150/150/?random" type="image/png" media="screen and (max-width: 150px)">
      <source srcset="https://picsum.photos/250/124/?random" type="image/png" media="screen and (max-width: 250px)">
      <source srcset="https://picsum.photos/768/380/?random" type="image/png" media="screen and (max-width: 768px)">
      <source srcset="https://picsum.photos/700/346/?random" type="image/png" media="screen and (max-width: 700px)">
      <source srcset="https://picsum.photos/120/59/?random" type="image/png" media="screen and (max-width: 120px)">
      <source srcset="https://picsum.photos/700/200/?random" type="image/png" media="screen and (max-width: 700px)">
      <source srcset="https://picsum.photos/700/346/?random" type="image/png" media="screen and (min-width: 1401px)">
      <img class="card__image" src="https://picsum.photos/700/346/?random">
    </picture>
    <div class="card__content">
      <div class="card__meta">02 October 2018 • Insights</div>
      <h2 class="card__title">This is a longer title to test what we can do to enforce a limit …</h2>
      <h3 class="card__subtitle"></h3>
      <p class="card__text">Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestib…</p>
    </div>
  </a>
</div>
```
