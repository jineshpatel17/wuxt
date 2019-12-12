---
name: Fonts
category: Helper Classes
---

## BEM Font classes

Each of the font variables have been mapped to BEM style classes that can be added
to any component or element.

Styling for fonts can be added to the sass for the components themselves,
however, in some instances it is better to add a modifier class to the component.

```scss
// Font weights
.fwt--light
.fwt--regular
.fwt--semibold
.fwt--bold
.fwt--extra-bold
.fwt--black

```

```font-weight-helper-classes.html
<div class="row">
  <div class="example">
    <strong class="annotationbeta">.fwt--light</strong>
    <div class="fwt--light">
      <p>Lorem ipsum dolor</p>
    </div>
  </div>
  <div class="example">
    <strong class="annotation">.fwt--regular</strong>
    <div class="fwt--regular">
      <p>Lorem ipsum dolor</p>
    </div>
  </div>
  <div class="example">
    <strong class="annotation">.fwt--semibold</strong>
    <div class="fwt--semibold">
      <p>Lorem ipsum dolor</p>
    </div>
  </div>
  <div class="example">
    <strong class="annotation">.fwt--bold</strong>
    <div class="fwt--bold">
      <p>Lorem ipsum dolor</p>
    </div>
  </div>
  <div class="example">
    <strong class="annotation">.fwt--extra-bold</strong>
    <div class="fwt--extra-bold">
      <p>Lorem ipsum dolor</p>
    </div>
  </div>
  <div class="example">
    <strong class="annotation">.fwt--black</strong>
    <div class="fwt--black">
      <p>Lorem ipsum dolor</p>
    </div>
  </div>      
</div>
```
