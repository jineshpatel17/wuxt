---
name: Colors
category: Helper Classes
---

## BEM Color classes

Each of the color variables have been mapped to BEM style classes that can be added
to any component or element.

Styling for colors can be added to the sass for the components themselves,
however, in some instances it is better to add a modifier class to the component.

The available classes are broken down into 3 categories of helper class:

```scss
// Backgrounds
.bg--light-alpha
.bg--light-beta

.bg--dark-alpha
.bg--dark-beta

.bg--action-alpha
.bg--action-beta

.bg--accent-alpha
.bg--accent-beta

.bg--highlight-alpha
.bg--highlight-beta
```


```bg-helper-classes.html
<div class="example-dark row">
  <div class="example">
    <strong class="annotation">.bg--light-alpha</strong>
    <div class="colors-example bg-example bg--light-alpha clr--light-alpha">
      <p>Lorem ipsum dolor</p>
    </div>
  </div>

  <div class="example">
    <strong class="annotation">.bg--light-beta</strong>
    <div class="colors-example bg-example bg--light-beta clr--light-alpha">
      <p>Lorem ipsum dolor</p>
    </div>
  </div>

  <div class="example">
    <strong class="annotation">.bg--dark-alpha</strong>
    <div class="colors-example bg-example bg--dark-alpha clr--light-alpha">
      <p>Lorem ipsum dolor</p>
    </div>
  </div>

  <div class="example">
    <strong class="annotation">.bg--dark-beta</strong>
    <div class="colors-example bg-example bg--dark-beta clr--light-alpha">
      <p>Lorem ipsum dolor</p>
    </div>
  </div>

  <div class="example">
    <strong class="annotation">.bg--action-alpha</strong>
    <div class="colors-example bg-example bg--action-alpha clr--light-alpha">
      <p>Lorem ipsum dolor</p>
    </div>
  </div>

  <div class="example">
    <strong class="annotation">.bg--action-beta</strong>
    <div class="colors-example bg-example bg--action-beta clr--light-alpha">
      <p>Lorem ipsum dolor</p>
    </div>
  </div>

  <div class="example">
    <strong class="annotation">.bg--accent-alpha</strong>
    <div class="colors-example bg-example bg--accent-alpha clr--light-alpha">
      <p>Lorem ipsum dolor</p>
    </div>
  </div>

  <div class="example">
    <strong class="annotation">.bg--accent-beta</strong>
    <div class="colors-example bg-example bg--accent-beta clr--light-alpha">
      <p>Lorem ipsum dolor</p>
    </div>
  </div>

  <div class="example">
    <strong class="annotation">.bg--highlight-alpha</strong>
    <div class="colors-example bg-example bg--highlight-alpha clr--light-alpha">
      <p>Lorem ipsum dolor</p>
    </div>
  </div>

  <div class="example">
    <strong class="annotation">.bg--highlight-beta</strong>
    <div class="colors-example bg-example bg--highlight-beta clr--light-alpha">
      <p>Lorem ipsum dolor</p>
    </div>
  </div>
</div>
```

```scss
// Text
.clr--light-alpha
.clr--light-beta

.clr--dark-alpha
.clr--dark-beta

.clr--action-alpha
.clr--action-beta

.clr--accent-alpha
.clr--accent-beta

.clr--highlight-alpha
.clr--highlight-beta
```

```clr-helper-classes.html
<div class="example-dark row">
  <div class="example">
    <strong class="annotation">.clr--light-alpha</strong>
    <div class="colors-example clr-example clr--light-alpha">
      <p>Lorem ipsum dolor</p>
    </div>
  </div>

  <div class="example">
    <strong class="annotation">.clr--light-beta</strong>
    <div class="colors-example clr-example clr--light-beta">
      <p>Lorem ipsum dolor</p>
    </div>
  </div>

  <div class="example">
    <strong class="annotation">.clr--dark-alpha</strong>
    <div class="colors-example clr-example clr--dark-alpha">
      <p>Lorem ipsum dolor</p>
    </div>
  </div>

  <div class="example">
    <strong class="annotation">.clr--dark-beta</strong>
    <div class="colors-example clr-example clr--dark-beta">
      <p>Lorem ipsum dolor</p>
    </div>
  </div>

  <div class="example">
    <strong class="annotation">.clr--action-alpha</strong>
    <div class="colors-example clr-example clr--action-alpha">
      <p>Lorem ipsum dolor</p>
    </div>
  </div>

  <div class="example">
    <strong class="annotation">.clr--action-beta</strong>
    <div class="colors-example clr-example clr--action-beta">
      <p>Lorem ipsum dolor</p>
    </div>
  </div>

  <div class="example">
    <strong class="annotation">.clr--accent-alpha</strong>
    <div class="colors-example clr-example clr--accent-alpha">
      <p>Lorem ipsum dolor</p>
    </div>
  </div>

  <div class="example">
    <strong class="annotation">.clr--accent-beta</strong>
    <div class="colors-example clr-example clr--accent-beta">
      <p>Lorem ipsum dolor</p>
    </div>
  </div>

  <div class="example">
    <strong class="annotation">.clr--highlight-alpha</strong>
    <div class="colors-example clr-example clr--highlight-alpha">
      <p>Lorem ipsum dolor</p>
    </div>
  </div>

  <div class="example">
    <strong class="annotation">.clr--highlight-beta</strong>
    <div class="colors-example clr-example clr--highlight-beta">
      <p>Lorem ipsum dolor</p>
    </div>
  </div>
</div>
```

```scss
// Borders
.bdr--light-alpha
.bdr--light-beta

.bdr--dark-alpha
.bdr--dark-beta

.bdr--action-alpha
.bdr--action-beta

.bdr--accent-alpha
.bdr--accent-beta

.bdr--highlight-alpha
.bdr--highlight-beta
```

```bdr-helper-classes.html
<div class="example-dark row">
  <div class="example">
    <strong class="annotation">.bdr--light-alpha</strong>
    <div class="colors-example bdr-example bdr--light-alpha">
      <p>Lorem ipsum dolor</p>
    </div>
  </div>

  <div class="example">
    <strong class="annotation">.bdr--light-beta</strong>
    <div class="colors-example bdr-example bdr--light-beta">
      <p>Lorem ipsum dolor</p>
    </div>
  </div>

  <div class="example">
    <strong class="annotation">.bdr--dark-alpha</strong>
    <div class="colors-example bdr-example bdr--dark-alpha">
      <p>Lorem ipsum dolor</p>
    </div>
  </div>

  <div class="example">
    <strong class="annotation">.bdr--dark-beta</strong>
    <div class="colors-example bdr-example bdr--dark-beta">
      <p>Lorem ipsum dolor</p>
    </div>
  </div>

  <div class="example">
    <strong class="annotation">.bdr--action-alpha</strong>
    <div class="colors-example bdr-example bdr--action-alpha">
      <p>Lorem ipsum dolor</p>
    </div>
  </div>

  <div class="example">
    <strong class="annotation">.bdr--action-beta</strong>
    <div class="colors-example bdr-example bdr--action-beta">
      <p>Lorem ipsum dolor</p>
    </div>
  </div>

  <div class="example">
    <strong class="annotation">.bdr--accent-alpha</strong>
    <div class="colors-example bdr-example bdr--accent-alpha">
      <p>Lorem ipsum dolor</p>
    </div>
  </div>

  <div class="example">
    <strong class="annotation">.bdr--accent-beta</strong>
    <div class="colors-example bdr-example bdr--accent-beta">
      <p>Lorem ipsum dolor</p>
    </div>
  </div>

  <div class="example">
    <strong class="annotation">.bdr--highlight-alpha</strong>
    <div class="colors-example bdr-example bdr--highlight-alpha">
      <p>Lorem ipsum dolor</p>
    </div>
  </div>

  <div class="example">
    <strong class="annotation">.bdr--highlight-beta</strong>
    <div class="colors-example bdr-example bdr--highlight-beta">
      <p>Lorem ipsum dolor</p>
    </div>
  </div>
</div>
```
