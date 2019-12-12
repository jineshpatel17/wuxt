export default function main() {
  try {
    const storage = window.localStorage;
    return storage;
  } catch (e) {
    return false;
  }
}
