export default function postApi(
  url,
  options = { body: {}, method: "POST" },
) {
  return fetch(url, {
    method: options.method,
    headers: {
      "Content-Type": "application/ld+json",
    },
    body: JSON.stringify(options.body),
  })
    .then((res) => res.json())
    .catch((error) => error);
}
