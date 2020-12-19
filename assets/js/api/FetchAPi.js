export default function fetchApi(
  url,
  options = { body: {}, method: "GET" },
  callback,
  firstElementOnly = false,
) {
  fetch(url, {
    method: options.method || "GET",
    headers: {
      "Content-Type": "application/ld+json",
    },
    body: options.method !== "GET" ? JSON.stringify(options.body) : null,
  })
    .then(async (response) => {
      const data = await response.json();
      if (data["@type"] === "hydra:Collection" && firstElementOnly) {
        callback(data["hydra:member"][0]);

        return;
      }
      callback(data, !response.ok, false);
    })
    .catch(() => {
      callback({ status: "network_failure" }, true, false);
    });
}
